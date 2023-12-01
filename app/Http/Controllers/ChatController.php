<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use http\Client\Response;
use \Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSockets\WebSocketServer;
use App\Models\ChMessage;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Chatify\ChatifyMessenger;
use function PHPUnit\Framework\isNull;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    use \App\Traits\Fields;
    protected $perPage = 30;
    public function __construct()
    {

    }

    public function index(\Illuminate\Http\Request $request)
    {

        $user = Auth::user();
        return view('chat', [
            'data' => json_encode([
                'user' => $user
            ])
        ]);
    }

    public function startWebSocketServer()
    {
        $webSocketServer = new WebSocketServer();
        $server = IoServer::factory(
            new HttpServer(
                new WsServer($webSocketServer)
            ),
            8080
        );

        $server->run();
    }

    public function stopWebSocketServer()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketServer()
                )
            ),
            8080
        );

        $server->loop->stop();
    }

    public function getUser(Request $request){

        $data = $request->all();

        $user = \App\Models\User::find($data['id']);

        $currentUser = Auth::user();

        $dataUser = [];

        if($user) {

            $messages = \App\Models\ChMessage::where('from_id', $currentUser->id)->where('to_id', $user->id)->where('delete_from', 0)
                ->orWhere('from_id', $user->id)->where('to_id', $currentUser->id)->where('delete_to', 0)->orderBy('created_at', 'asc')->get();
            $groupedMessages = [];
            if ($messages) {

                $groupedMessages = $messages->groupBy(function ($message) {
                    return $message->created_at->format('Y-m-d');
                });
                $groupedMessages->transform(function ($messages, $date) {
                    return $messages->map(function ($message) {

                        $messageBody = $message->body;
                        $messageDelete = false;
                        $isFile = false;

                        if ($message->from_id == Auth::user()->id) {
                            $messageBody = $message->hide_from == 1 ? 'Удаленное сообщение' : $message->body;
                            $messageDelete = $message->hide_from == 1 ?? true;
                        } else {
                            $messageBody = $message->hide_to == 1 ? 'Удаленное сообщение' : $message->body;
                            $messageDelete = $message->hide_to == 1 ?? true;
                        }
                        if(!$messageBody){
                            if ($message->attachment) {
                                $attachmentData = json_decode($message->attachment);
                                $messageBody = $this->getFileType($attachmentData->new_name);//html_entity_decode("<img src='/storage/attachments/{$attachmentData->new_name}'>");
                                $isFile = true;
                            } elseif ($message->audio_file) {
                                $messageBody = html_entity_decode("<audio controls><source src='/storage/audio/{$message->audio_file}' type='audio/webm'>Ваш браузер не поддерживает аудиофайлы WebM.</audio>");
                                $isFile = true;
                            }
                        }

                        $reply = null;
                        if(!is_null($message->reply_id)){
                            $replyMessage = \App\Models\ChMessage::find($message->reply_id);
                            if($replyMessage){
                                $replyMessageBody = $replyMessage->body;
                                if ($message->from_id == Auth::user()->id) {
                                    $replyMessageBody = $message->hide_from == 1 ? 'Удаленное сообщение' : $replyMessage->body;
                                } else {
                                    $replyMessageBody = $message->hide_to == 1 ? 'Удаленное сообщение' : $replyMessage->body;
                                }
                                if(!empty($replyMessageBody)){
                                    if (!is_null($message->attachment)) {
                                        $replyAttachmentData = json_decode($message->attachment);
                                        $replyMessageBody = $this->getFileType($replyAttachmentData->new_name); //html_entity_decode("<img src='/storage/attachments/{$replyAttachmentData->new_name}'>");
                                    } elseif (!is_null($message->audio_file)) {
                                        $replyMessageBody = $this->getFileType($replyMessageBody->audio_file); //html_entity_decode("<audio controls><source src='/storage/audio/{$replyMessageBody->audio_file}' type='audio/webm'>Ваш браузер не поддерживает аудиофайлы WebM.</audio>");
                                    }
                                }

                                $reply_from_id = \App\Models\User::find($replyMessage->from_id);
                                if(!is_null($reply_from_id)){
                                    $reply_from_id = [
                                        'id' => $reply_from_id->id,
                                        'first_name' => $reply_from_id->first_name,
                                        'last_name' => $reply_from_id->last_name
                                    ];
                                }
                                $reply = [
                                    'id' => $replyMessage->id,
                                    'from_id' => $reply_from_id,
                                    'reply_message' => $replyMessageBody
                                ];
                            }
                        }
                        $forwardUser = null;
                        if(!is_null($message->forward_from)){
                            $forward_from = \App\Models\User::where('id', $message->forward_from)->first();
                            if($forward_from){
                                $forwardUser = array(
                                    'first_name' => $forward_from->first_name,
                                    'image' => $forward_from->image ? '/storage/' . $forward_from->image : '/image/avatar.png'
                                );
                            }

                        }

                        $reactions = $message->reactions()->get();
                        $emojies = array();
                        if($reactions){
                            foreach($reactions as $reaction){
                                array_push($emojies, $reaction->reaction);
//                                $emojies .= $reaction->reaction;
                            }
                        }else{
                            $emojies = null;
                        }

                            return [
                                'id' => $message->id,
                                'direction' => $message->from_id == Auth::user()->id ? 'sent' : 'reseived',
                                'from_id' => $message->from_id,
                                'to_id' => $message->to_id,
                                'delete' => $messageDelete,
                                'isFile' => $isFile,
                                'message' => $messageBody,
                                'date' => $message->created_at->format('H:i'),
                                'reply' => $reply,
                                'reaction' => $emojies,
                                'forwarded' => !($message->forwarded == 0),
                                'forward_from' => $forwardUser
                        ];
                    });
                });
            }

            $getRecords = '';
            if ($groupedMessages) {
                foreach ($groupedMessages as $key => $message) {
                    $date = $this->formatDateToObject($key);
                    $getRecords .= view('Chatify::layouts.listItem', [
                        'get' => 'messageList',
                        'key' => $date['date'],
                        'messages' => $message
                    ]);

                }
            }


            $dataUser = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'image' => $user->image,
                'last_online_at' => $user->last_online_at ? $user->last_online_at->format('Y-m-d H:i:s'): null
            ];

            return response()->json(['status' => true, 'user' => $dataUser, 'messages' => $getRecords]);


        }
        return response()->json(['status' => false, 'error' => 'Ошибка при получении данных с сервера']);

    }

    public function saveAudio(Request $request)
    {

        $audioPath = $request->file('audio')->store('/public/audio/');

        $message = \App\Models\ChMessage::create([
            'from_id' => Auth::user()->id,
            'to_id' => $request->user_id,
            'audio_file' => basename($audioPath),
        ]);

        if($message){
            $msg = null;
            if($message->reply_id){
                $msg = \App\Models\ChMessage::find($message->reply_id);
                $replyMessageBody = $msg->body;
                if ($msg->attachment !== null) {
                    $replyAttachmentData = json_decode($msg->attachment);
                    $replyMessageBody = $this->getFileType($replyAttachmentData->new_name);  //html_entity_decode("<img src='/storage/attachments/{$replyAttachmentData->new_name}'>");
                } elseif ($msg->audio_file !== null) {
                    $replyMessageBody = html_entity_decode("<audio controls><source src='/storage/audio/{$msg->audio_file}' type='audio/webm'>Ваш браузер не поддерживает аудиофайлы WebM.</audio>");
                } elseif ($msg->from_id == Auth::user()->id) {
                    $replyMessageBody = $msg->hide_from == 1 ? 'Удаленное сообщение' : $msg->body;
                } else {
                    $replyMessageBody = $msg->hide_to == 1 ? 'Удаленное сообщение' : $msg->body;
                }
                $reply_from_id = \App\Models\User::find($msg->from_id);
                if($reply_from_id){
                    $reply_from_id = [
                        'id' => $reply_from_id->id,
                        'first_name' => $reply_from_id->first_name,
                        'last_name' => $reply_from_id->last_name
                    ];
                }
                $msg = [
                    'id' => $msg->id,
                    'from_id' => $reply_from_id,
                    'reply_message' => $replyMessageBody
                ];
            }
            $reactions = $message->reactions()->first();
            if($reactions){
                $reaction = $reactions->reaction;
            }else{
                $reaction = null;
            }

            $dataMessage = [];

            $dataMessage['message'] = html_entity_decode("<audio controls><source src='/storage/audio/$message->audio_file' type='audio/webm'>Ваш браузер не поддерживает аудиофайлы WebM.</audio>");
            $dateTime = $this->formatMessageDate($message->created_at);
            $dataMessage['dateTitle'] = $dateTime['date'];
            $dataMessage['timeTitle'] = $dateTime['time'];
            $dataMessage['direction'] = true;
            $dataMessage['direction'] = 'sent';
            $dataMessage['id'] = $message->id;
            $dataMessage['reply'] = $msg;
            $dataMessage['delete'] = false;
            $dataMessage['date'] = $message->created_at->format('H:i');
            $dataMessage['reaction'] = $reaction;
            $dataMessage['isFile'] = false;
            $dataMessage['forwarded'] = false;

            $messages[] = $dataMessage;

            $view = View::make('Chatify::layouts.listItem', ['get' => 'messageList','key'=>$message->created_at->format('Y-m-d'), 'messages'=> $messages])->render();

            if(!empty($view)){
                return response()->json(['success' => true, 'tempID' => $request->tempID, 'message' => $view]);
            }else{
                return response()->json(['success' => false, 'error' => 'Ошибка при рендере шаблона']);
            }

        }else{
            return response()->json(['success' => false, 'error' => 'Ошибка сохранения голосового сообщения']);
        }

    }

    public function saveAudioChat(Request $request)
    {

        $audioPath = $request->file('audio')->store('/public/audio/');

        $message = \App\Models\ChGroupMessages::create([
            'ch_group_id' => $request->chat_id,
            'user_id' => Auth::user()->id,
            'audio_file' => basename($audioPath),
        ]);

        if($message){
            $msg = null;
            if($message->reply_id){
                $msg = \App\Models\ChGroupMessages::find($message->reply_id);
                $replyMessageBody = $msg->body;
                if ($msg->attachments !== null) {
                    $replyAttachmentData = json_decode($msg->attachments);
                    $replyMessageBody = $this->getFileType($replyAttachmentData->new_name);  //html_entity_decode("<img src='/storage/attachments/{$replyAttachmentData->new_name}'>");
                } elseif ($msg->audio_file !== null) {
                    $replyMessageBody = html_entity_decode("<audio controls><source src='/storage/audio/{$msg->audio_file}' type='audio/webm'>Ваш браузер не поддерживает аудиофайлы WebM.</audio>");
                } elseif ($msg->from_id == Auth::user()->id) {
                    $replyMessageBody = $msg->hide_from == 1 ? 'Удаленное сообщение' : $msg->body;
                } else {
                    $replyMessageBody = $msg->hide_to == 1 ? 'Удаленное сообщение' : $msg->body;
                }
                $reply_from_id = \App\Models\User::find($msg->from_id);
                if($reply_from_id){
                    $reply_from_id = [
                        'id' => $reply_from_id->id,
                        'first_name' => $reply_from_id->first_name,
                        'last_name' => $reply_from_id->last_name
                    ];
                }
                $msg = [
                    'id' => $msg->id,
                    'from_id' => $reply_from_id,
                    'reply_message' => $replyMessageBody
                ];
            }
//            $reactions = \App\Models\ChUserReaction::where('ch_message_id', $message->id)->get();//$message->reactions()->first();
//            if($reactions){
//                $reaction = $reactions->reaction;
//            }else{
//                $reaction = null;
//            }
            $reaction = null;

            $dataMessage = [];

            $dataMessage['message'] = html_entity_decode("<audio controls><source src='/storage/audio/$message->audio_file' type='audio/webm'>Ваш браузер не поддерживает аудиофайлы WebM.</audio>");
            $dateTime = $this->formatMessageDate($message->created_at);
            $dataMessage['dateTitle'] = $dateTime['date'];
            $dataMessage['timeTitle'] = $dateTime['time'];
            $dataMessage['direction'] = true;
            $dataMessage['direction'] = 'sent';
            $dataMessage['id'] = $message->id;
            $dataMessage['reply'] = $msg;
            $dataMessage['delete'] = false;
            $dataMessage['date'] = $message->created_at->format('H:i');
            $dataMessage['reaction'] = $reaction;
            $dataMessage['isFile'] = false;
            $dataMessage['forwarded'] = false;

            $messages[] = $dataMessage;

            $view = View::make('Chatify::layouts.listItem', ['get' => 'messageList','key'=>$message->created_at->format('Y-m-d'), 'messages'=> $messages])->render();

            if(!empty($view)){
                return response()->json(['success' => true, 'tempID' => $request->tempID, 'message' => $view]);
            }else{
                return response()->json(['success' => false, 'error' => 'Ошибка при рендере шаблона']);
            }

        }else{
            return response()->json(['success' => false, 'error' => 'Ошибка сохранения голосового сообщения']);
        }

    }

    public function setReadMessage(Request $request){

        $currentUser = Auth::user();

        $folder = \App\Models\ChFolders::where('id', $request->tabId)->first();
        $contacts = $this->getUsersForChat();
        if($folder){
            if($folder->users != null){
                $usersIds = explode(", ", $folder->users);
                $usersIds = array_map("intval", $usersIds);
                $users = \App\Models\User::whereIn('id', $usersIds)->get();
                foreach($users as $user){
                    $messages = \App\Models\ChMessage::where('to_id', $currentUser->id)->where('from_id', $user->id)->where('seen', 0)->get();
                    foreach ($messages as $message){
                        $message->seen = 1;
                        $message->save();
                    }
                }
            }else{
                $users = $this->getUsersForChat();
                foreach($users as $user){
                    $messages = \App\Models\ChMessage::where('to_id', $currentUser->id)->where('from_id', $user['id'])->where('seen', 0)->get();
                    foreach ($messages as $message){
                        $message->seen = 1;
                        $message->save();
                    }
                }

            }

            $contacts = $this->getUsersForChat();

            if($folder->users != null){

                $userIds = explode(", ", $folder->users);
                $userIds = array_map("intval", $userIds);
                $contacts = $this->getUsersForChat();
                $users = array_filter($contacts, function ($item) use ($userIds) {
                    return in_array($item['id'], $userIds);
                });

                $sortedArray = collect($users)->sortBy(function ($item) {
                    return optional($item['lastMessage'])['created_at'];
                })->all();

                usort($sortedArray, function ($a, $b) {
                    if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                        return 0;
                    } elseif ($a['lastMessage'] === null) {
                        return 1;
                    } elseif ($b['lastMessage'] === null) {
                        return -1;
                    }
                    return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                });


            }else{
                $users = $contacts;
                $sortedArray = collect($users)->sortBy(function ($item) {
                    return optional($item['lastMessage'])['created_at'];
                })->all();

                usort($sortedArray, function ($a, $b) {
                    if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                        return 0;
                    } elseif ($a['lastMessage'] === null) {
                        return 1;
                    } elseif ($b['lastMessage'] === null) {
                        return -1;
                    }
                    return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                });
            }

            $getRecords = '';

            foreach ($sortedArray as $user){

                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'listUsers',
                    'userId' => 0,
                    'user' => $user,
                    'id' => $request->userId
                ])->render();

            }

            $userFolders = \App\Models\ChFolders::where('user_id', Auth::user()->id)->orderBy('sort', 'asc')->get();
            $userFolders->transform(function ($folder) {
                $contacts = $this->getUsersForChat();
                if($folder->users != null){
                    $userIds = explode(", ", $folder->users);
                    $userIds = array_map("intval", $userIds);
                    $users = array_filter($contacts, function ($item) use ($userIds) {
                        return in_array($item['id'], $userIds);
                    });
                }else{
                    $users = $contacts;
                }

                $unreadMessages = 0;
                foreach($users as $user){
                    if($user['countMessages'] > 0){
                        $unreadMessages++;
                    }
                }

                return [
                    'id' => $folder->id,
                    'folder_name' => $folder->folder_name,
                    'countMessages' => $unreadMessages,
                    'delete' => !($folder->delete == 1),
                ];


            });

            $folders = '';
            foreach($userFolders as $folder){
                $folders .= view('Chatify::layouts.listItem', [
                    'get' => 'userFolders',
                    'folder' => $folder,
                    'id' => $request->tabId
                ])->render();
            }
            if($userFolders->count() > 0){
                $folders .= view('Chatify::layouts.listItem', ['get' => 'addFolder'])->render();
            }


            $unreadMessages = 0;
            $contacts = $this->getUsersForChat();
            foreach($contacts as $contact){
                if($contact['countMessages'] > 0){
                    $unreadMessages++;
                }
            }

            $userMenu = view('Chatify::layouts.listItem', [
                'get' => 'userMenu',
                'userId' => Auth::user()->id,
                'countMessages' => $unreadMessages
            ])->render();

            return response()->json([
                'status' => true,
                'contacts' => $getRecords,
                'userMenu' => $userMenu,
                'folders' => $folders
            ]);



        }

    }

    public function addFolder(Request $request){
        $data = $request->all();

        $user = Auth::user();

        if($user){

            $maxSortValue = $user->chatFolders->max('sort');

            $new_folder = \App\Models\ChFolders::create([
                'folder_name' => $data['folder_name'],
                'user_id' => $user->id,
                'sort' => $maxSortValue + 1,
                'users' => implode(", ", $data['users']),
                'delete' => 1
            ]);

            if($new_folder){
                $userFolders = \App\Models\ChFolders::where('user_id', Auth::user()->id)->get();

                $userFolders->transform(function ($folder) use($new_folder) {

                    $contacts = $this->getUsersForChat();
                    if($folder->users != null && getType($folder->users) == 'string'){
                        $userIds = explode(", ", $folder["users"]);
                        $userIds = array_map("intval", $userIds);
                        $usersData = array_filter($contacts, function ($item) use ($userIds) {
                            return in_array($item['id'], $userIds);
                        });
                        $sortedArray = collect($usersData)->sortBy(function ($item) {
                            return optional($item['lastMessage'])['created_at'];
                        })->all();

                        usort($sortedArray, function ($a, $b) {
                            if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                                return 0;
                            } elseif ($a['lastMessage'] === null) {
                                return 1;
                            } elseif ($b['lastMessage'] === null) {
                                return -1;
                            }
                            return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                        });


                    }else{
                        $usersData = $contacts;
                        $sortedArray = collect($usersData)->sortBy(function ($item) {
                            return optional($item['lastMessage'])['created_at'];
                        })->all();

                        usort($sortedArray, function ($a, $b) {
                            if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                                return 0;
                            } elseif ($a['lastMessage'] === null) {
                                return 1;
                            } elseif ($b['lastMessage'] === null) {
                                return -1;
                            }
                            return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                        });
                    }


                    $countMessages = 0;
                    foreach ($sortedArray as $item){
                        if($item['countMessages'] > 0){
                            $countMessages++;
                        }
                    }

                    return [
                        'id' => $folder->id,
                        'folder_name' => $folder->folder_name,
                        'delete' => $folder->delete == 1,
                        'countMessages' => $countMessages,
                        'users' => $sortedArray

                    ];

                });

                $getRecords = '';

                $getRecords = '';
                foreach ($userFolders as $folder){
                    $getRecords .= view('Chatify::layouts.listItem', [
                        'get' => 'userFolders',
                        'id' => $new_folder->id,
                        'folder' => $folder
                    ]);
                }
                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'addFolder'
                ]);

//                dd($userFolders);

                $userTabs = View::make('Chatify::layouts.listItem', [
                    'get' => 'userTab',
                    'folders' => $userFolders,
                    'id' => $new_folder->id
                ])->render();



                return response()->json(['status' => true, 'folders' => $getRecords, 'tabs' => $userTabs]);

            }

        }

    }

    public function getUserInfo(Request $request){

        $data = $request->all();

        $currentUser = Auth::user();

        $user = \App\Models\User::where('id', $request->id)->first();

        $userInfo = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'image' => $user->image,
            'view' => $user->view,
            'score' => $user->score,
            'subscribe' => $user->subscribe
        ];

        $properties = $this->getEntityFields($user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');

        $fieldsInline = ['signature', 'description'];
        $socials = ['socials'];

        foreach ($properties as $item){
            if(in_array($item['slug'], $socials)){
                if(isset($item['fields'])){
                    foreach ($item['fields'] as $field){
                        if(isset($field['value'])){
                            if($field['value'] != null){
                                $userInfo['socials'][$field['slug']] = $field['value'];
                            }

                        }
                    }
                }
            }else{
                if(isset($item['fields'])){
                    foreach ($item['fields'] as $field){
                        if(isset($field['value'])){
                            if(in_array($field['slug'], $fieldsInline) == true){
                                $userInfo[$field['slug']] = $field['value'] != null ? $field['value'] : '';
                            }
                        }
                    }
                }
            }
        }

        $settingsRecords = \App\Models\ChUsersSettings::where('user_id', $currentUser->id)->where('setting_user_id', $user->id)->get()->count();

        if($settingsRecords == 0){
            $settingRecord = $currentUser->chatSettings()->create([
                'setting_user_id' => $user->id,
                'important' => 0,
                'notifications' => 0
            ]);
        }else{
            $settingRecord = \App\Models\ChUsersSettings::where('user_id', $currentUser->id)->where('setting_user_id', $user->id)->first();
        }

        if($settingRecord){

            $userInfo['settings'][] = ['name' => 'important', 'title' => 'Важно', 'value' => $settingRecord->important == 1 ?? true];
            $userInfo['settings'][] = ['name' => 'notifications', 'title' => 'Присылать уведмления', 'value' => $settingRecord->notifications == 1 ?? true];

        }
        $data = [];
        $getRecords = '';
        foreach($userInfo['settings'] as $key=>$setting){
            $data['title'] = $setting['title'];
            $data['name'] = $setting['name'];
            $data['value'] = $setting['value'];
            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'profile_settings',
                'setting' => $data
            ]);

        }
        $userInfo['settings'] = $getRecords;

        return response()->json(['status' => true,'userInfo' => $userInfo]);

    }

    public function deleteHistory(Request $request){

        $data = $request->all();

        $currentUser = Auth::user();

        $user = \App\Models\User::find($data['user_id']);

        if($user != null && $currentUser != null){

            if($request->has('delete_from') && $request->delete_from == true){

                try {

                    \App\Models\ChMessage::where('from_id', $currentUser->id)->where('to_id', $user->id)->where('delete_from', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_from' => 1]);
                    \App\Models\ChMessage::where('from_id', $currentUser->id)->where('to_id', $user->id)->where('delete_from', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_to' => 1]);
                    \App\Models\ChMessage::where('to_id', $currentUser->id)->where('from_id', $user->id)->where('delete_to', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_to' => 1]);
                    \App\Models\ChMessage::where('to_id', $currentUser->id)->where('from_id', $user->id)->where('delete_to', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_from' => 1]);

                }catch (\Illuminate\Database\QueryException $e) {


                    return response()->json(['status' => false,'code' => $e->getCode(), 'message' => $e->getMessage()]);
                }

            }else{
                try {

                    \App\Models\ChMessage::where('from_id', $currentUser->id)->where('to_id', $user->id)->where('delete_from', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_from' => 1]);
                    \App\Models\ChMessage::where('to_id', $currentUser->id)->where('from_id', $user->id)->where('delete_to', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_to' => 1]);

                }catch (\Illuminate\Database\QueryException $e) {

                    return response()->json(['status' => false,'code' => $e->getCode(), 'message' => $e->getMessage()]);

                }

            }


            return response()->json(['status' => true]);

        }

        return response()->json(['status' => false]);
    }

    private function formatMessageDate($dateString): array
    {
        $messageDate = Carbon::parse($dateString);
        $now = Carbon::now();

        if ($messageDate->isSameDay($now)) {
            return [
                'date' => 'Сегодня',
                'time' => $messageDate->format('H:i:s')
            ];
        } elseif ($messageDate->isYesterday($now)) {
            return [
                'date' => 'Вчера',
                'time' => $messageDate->format('H:i:s')
            ];
        } else {
            // Если дата не сегодня и не вчера, вернуть массив с исходной датой и временем
            return [
                'date' => $messageDate->format('d.m.Y'),
                'time' => $messageDate->format('H:i:s')
            ];
        }
    }

    public function folderEdit(Request $request){

        $data = $request->all();

        if(Auth::user()){
            $usersData = [];
            $folder = \App\Models\ChFolders::where('id', $data['id'])->first();
            if($folder->folder_name == 'Все'){
                $usersData = $this->getUsersForChat();
                $sortedArray = collect($usersData)->sortBy(function ($item) {
                    return optional($item['lastMessage'])['created_at'];
                })->all();

                usort($sortedArray, function ($a, $b) {
                    if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                        return 0;
                    } elseif ($a['lastMessage'] === null) {
                        return 1;
                    } elseif ($b['lastMessage'] === null) {
                        return -1;
                    }
                    return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                });
            }else{
                if($folder->users != null){
                    $userIds = explode(", ", $folder->users);
                    $userIds = array_map('intval', $userIds);
                    $contacts = $this->getUsersForChat();
                    $usersData = array_filter($contacts, function ($item) use ($userIds) {
                        return in_array($item['id'], $userIds);
                    });
                    $sortedArray = collect($usersData)->sortBy(function ($item) {
                        return optional($item['lastMessage'])['created_at'];
                    })->all();

                    usort($sortedArray, function ($a, $b) {
                        if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                            return 0;
                        } elseif ($a['lastMessage'] === null) {
                            return 1;
                        } elseif ($b['lastMessage'] === null) {
                            return -1;
                        }
                        return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                    });
                }else{
                    $usersData = $this->getUsersForChat();
                    $sortedArray = collect($usersData)->sortBy(function ($item) {
                        return optional($item['lastMessage'])['created_at'];
                    })->all();

                    usort($sortedArray, function ($a, $b) {
                        if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                            return 0;
                        } elseif ($a['lastMessage'] === null) {
                            return 1;
                        } elseif ($b['lastMessage'] === null) {
                            return -1;
                        }
                        return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                    });
                }

            }

            $folders = [
                'sort' => $folder->sort,
                'countFolders' => \App\Models\ChFolders::where('user_id', Auth::user()->id)->get()->count()
            ];

            $view = '';
            foreach ($sortedArray as $data){

                $view .= View::make('Chatify::layouts.listItem', ['get' => 'userForModal','checked' => true, 'user'=> $data])->render();

            }

            return response()->json([
                'status' => true,
                'folders' => $folders,
                'contacts' => $view
            ]);
        }else{
            return response()->json([
                'status' => false,
                'error' => 'Ошибка аутентификации пользователя'
            ]);
        }





    }

    private function getUsersForChat(){

        $user = Auth::user();

        $userSubsctibers = $user->subscribers()->get();
        $userSubsctibers->transform(function ($record) {

            $subscriber = \App\Models\User::where('id', $record->user_subscribe_id)->first();


            if($subscriber){

                $last_message = null;
                $lm = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $subscriber->id)
                    ->orWhere('to_id', $subscriber->id)->where('from_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
                if($lm){
                    $image = null;
                    if($lm->attachment != null){
                        if($lm->from_id == Auth::user()->id){
                            $image = ($lm->hide_from == 1) ? null : $this->getImageFromAttachment($lm->attachment);
                        }else{
                            $image = ($lm->hide_to == 1) ? null : $this->getImageFromAttachment($lm->attachment);
                        }
                    }
                    $messageDelete = false;
                    $from_id = \App\Models\User::find($lm->from_id);
                    if($from_id){
                        $from_id = [
                            'id' => $from_id->id,
                            'first_name' => $from_id->first_name,
                            'last_name' => $from_id->last_name,
                            'image' => $from_id->image ? '/storage/' . $from_id->image : '/image/avatar.png',
                            'last_online_at' => $from_id->last_online_at ? $from_id->last_online_at->format('Y-m-d H:i:s') : null
                        ];
                    }
                    if($lm->from_id == Auth::user()->id){
                        $body = $lm->hide_from ? 'Удаленное сообщение' : $lm->body;
                        $attachment = $lm->hide_from ? 'Удаленное сообщение' : $lm->attachment;
                        $audio = $lm->hide_from ? 'Удаленное сообщение' : $lm->audio_file;
                    }else{
                        $body = $lm->hide_to ? 'Удаленное сообщение' : $lm->body;
                        $attachment = $lm->hide_to ? 'Удаленное сообщение' : $lm->attachment;
                        $audio = $lm->hide_to ? 'Удаленное сообщение' : $lm->audio_file;
                    }
                    if($lm->from_id == Auth::user()->id){
                        if($lm->delete_from){
                            $messageDelete = true;
                            $image = null;
                        }
                    }else{
                        if($lm->delete_to){
                            $messageDelete = true;
                            $image = null;
                        }
                    }
                    if($messageDelete){
                        $last_message = null;
                    }else{
                        $last_message = [
                            'id' => $lm->id,
                            'from_id' => $from_id,
                            'body' => $body,
                            'attachment' => $attachment,
                            'image' => $image,
                            'audio' => $audio,
                            'seen' => $lm->seen,
                            'created_at' => $lm->created_at->format('Y-m-d H:i:s')
                        ];

                    }
                }

                $countMessages = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $subscriber->id)->where('seen', 0)->get()->count();

                $subscriber = [
                    'id' => $subscriber->id,
                    'first_name' => $subscriber->first_name,
                    'last_name' => $subscriber->last_name,
                    'image' => $subscriber->image ? '/storage/' . $subscriber->image : '/image/avatar.png',
                    'last_online_at' => $subscriber->last_online_at ? $subscriber->last_online_at->format('Y-m-d H:i:s'): null,
                    'lastMessage' => $last_message,
                    'countMessages' => $countMessages
                ];

            }

            return [
                'id' => $subscriber['id'],
                'first_name' => $subscriber['first_name'],
                'last_name' => $subscriber['last_name'],
                'image' => $subscriber['image'],
                'last_online_at' => $subscriber['last_online_at'],
                'lastMessage' => $subscriber['lastMessage'],
                'countMessages' => $subscriber['countMessages']
            ];


        });

        $referals = $user->referals()->get();

        $referals->transform(function ($record) {

            $referal = \App\Models\User::find($record->referal_id);

            if($referal){
                $last_message = null;
                $lm = \App\Models\ChMessage::where('to_id', $record->user_id)->where('from_id', $record->referal_id)
                    ->orWhere('to_id', $record->referal_id)->where('from_id', $record->user_id)->orderBy('created_at', 'DESC')->first();
                if($lm){
                    $messageDelete = false;
                    $image = null;
                    if($lm->attachment != null){
                        if($lm->from_id == Auth::user()->id){
                            $image = ($lm->hide_from == 1) ? null : $this->getImageFromAttachment($lm->attachment);
                        }else{
                            $image = ($lm->hide_to == 1) ? null : $this->getImageFromAttachment($lm->attachment);
                        }
                    }

                    $from_id = \App\Models\User::find($lm->from_id);
                    if($from_id){
                        $from_id = [
                            'id' => $from_id->id,
                            'first_name' => $from_id->first_name,
                            'last_name' => $from_id->last_name,
                            'image' => $from_id->image ? '/storage/' . $from_id->image : '/image/avatar.png',
                            'last_online_at' => $from_id->last_online_at ? $from_id->last_online_at->format('Y-m-d H:i:s') : null,
                        ];
                    }
                    if($lm->from_id == Auth::user()->id){
                        $body = $lm->hide_from ? 'Удаленное сообщение' : $lm->body;
                        $attachment = $lm->hide_from ? 'Удаленное сообщение' : $lm->attachment;
                        $audio = $lm->hide_from ? 'Удаленное сообщение' : $lm->audio_file;
                    }else{
                        $body = $lm->hide_to ? 'Удаленное сообщение' : $lm->body;
                        $attachment = $lm->hide_to ? 'Удаленное сообщение' : $lm->attachment;
                        $audio = $lm->hide_to ? 'Удаленное сообщение' : $lm->audio_file;
                    }
                    if($lm->from_id == Auth::user()->id){
                        if($lm->delete_from){
                            $messageDelete = true;
                        }
                    }else{
                        if($lm->delete_to){
                            $messageDelete = true;
                        }
                    }

                    if($messageDelete){
                        $last_message = null;
                    }else{
                        $last_message = [
                            'id' => $lm->id,
                            'from_id' => $from_id,
                            'body' => $body,
                            'image' => $image,
                            'attachment' => $attachment,
                            'audio' => $audio,
                            'seen' => $lm->seen,
                            'created_at' => $lm->created_at->format('Y-m-d H:i:s')
                        ];

                    }

                }
                $countMessages = \App\Models\ChMessage::where('to_id', $record->user_id)->where('from_id', $record->referal_id)->where('seen', 0)->get()->count();


                $referal = [
                    'id' => $referal->id,
                    'first_name' => $referal->first_name,
                    'last_name' => $referal->last_name,
                    'image' => $referal->image ? '/storage/' . $referal->image : '/image/avatar.png',
                    'last_online_at' => $referal->last_online_at ? $referal->last_online_at->format('Y-M-d H:i:s') : null,
                    'lastMessage'=> $last_message,
                    'countMessages' => $countMessages
                ];
            }

            if($referal){
                return [
                    'id' => $referal['id'],
                    'first_name' => $referal['first_name'],
                    'last_name' => $referal['last_name'],
                    'image' => $referal['image'],
                    'last_online_at' => $referal['last_online_at'],
                    'lastMessage' => $referal['lastMessage'],
                    'countMessages' => $referal['countMessages']
                ];

            }else{
                return [];
            }

        });

        $userSubscribe = \App\Models\UserSubscribe::where('user_subscribe_id', Auth::user()->id)->get();

        $userSubscribe->transform(function ($record) {

            $subscriber = \App\Models\User::find($record->user_id);
            $last_message = null;
            if($subscriber){
                $lm = \App\Models\ChMessage::where('to_id', $record->user_id)->where('from_id', $record->user_subscribe_id)
                    ->orWhere('to_id', $record->user_subscribe_id)->where('from_id', $record->user_id)->orderBy('created_at', 'DESC')->first();
                $last_message = null;
                if($lm){
                    $messageDelete = false;
                    $image = null;
                    if($lm->attachment != null){
                        if($lm->from_id == Auth::user()->id){
                            $image = ($lm->hide_from == 1) ? null : $this->getImageFromAttachment($lm->attachment);
                        }else{
                            $image = ($lm->hide_to == 1) ? null : $this->getImageFromAttachment($lm->attachment);
                        }
                    }

                    $from_id = \App\Models\User::find($lm->from_id);
                    if($from_id){
                        $from_id = [
                            'id' => $from_id->id,
                            'first_name' => $from_id->first_name,
                            'last_name' => $from_id->last_name,
                            'image' => $from_id->image ? '/storage/' . $from_id->image : '/image/avatar.png',
                            'last_online_at' => $from_id->last_online_at ? $from_id->last_online_at->format('Y-m-d H:i:s') : null,
                        ];
                    }
                    if($lm->from_id == Auth::user()->id){
                        $body = $lm->hide_from ? 'Удаленное сообщение' : $lm->body;
                        $attachment = $lm->hide_from ? 'Удаленное сообщение' : $lm->attachment;
                        $audio = $lm->hide_from ? 'Удаленное сообщение' : $lm->audio_file;
                    }else{
                        $body = $lm->hide_to ? 'Удаленное сообщение' : $lm->body;
                        $attachment = $lm->hide_to ? 'Удаленное сообщение' : $lm->attachment;
                        $audio = $lm->hide_to ? 'Удаленное сообщение' : $lm->audio_file;
                    }
                    if($lm->from_id == Auth::user()->id){
                        if($lm->delete_from){
                            $messageDelete = true;
                            $image = null;
                        }
                    }else{
                        if($lm->delete_to){
                            $messageDelete = true;
                            $image = null;
                        }
                    }

                    if($messageDelete){
                        $last_message = null;
                    }else{
                        $last_message = [
                            'id' => $lm->id,
                            'from_id' => $from_id,
                            'body' => $body,
                            'image' => $image,
                            'attachment' => $attachment,
                            'audio' => $audio,
                            'seen' => $lm->seen,
                            'created_at' => $lm->created_at->format('Y-m-d H:i:s')
                        ];
                    }

                }
                $countMessages = \App\Models\ChMessage::where('to_id', $record->user_subscribe_id)->where('from_id', $record->user_id)->where('seen', 0)->get()->count();

                return [
                    'id' => $subscriber->id,
                    'first_name' => $subscriber->first_name,
                    'last_name' => $subscriber->last_name,
                    'image' => $subscriber->image ? '/storage/'. $subscriber->image : '/image/avatar.png',
                    'last_online_at' => $subscriber->last_online_at ? $subscriber->last_online_at->format('Y-m-d H:i:s') : null,
                    'lastMessage' => $last_message,
                    'countMessages' => $countMessages
                ];

            }

        });


        $contacts = [];

        foreach ($userSubsctibers as $userSubsctiber){
            if(!in_array($userSubsctiber, $contacts)){
                $contacts[$userSubsctiber['id']] = [
                    'id' => $userSubsctiber['id'],
                    'first_name' => $userSubsctiber['first_name'],
                    'last_name' => $userSubsctiber['last_name'],
                    'image' => $userSubsctiber['image'],
                    'last_online_at' => $userSubsctiber['last_online_at'],
                    'lastMessage' => $userSubsctiber['lastMessage'],
                    'countMessages' => $userSubsctiber['countMessages']
                ];
            }

        }

        foreach ($referals as $referal){
            if($referal){
                if(!in_array($referal, $contacts)){
                    $contacts[$referal['id']] = [
                        'id' => $referal['id'],
                        'first_name' => $referal['first_name'],
                        'last_name' => $referal['last_name'],
                        'image' => $referal['image'],
                        'last_online_at' => $referal['last_online_at'],
                        'lastMessage' => $referal['lastMessage'],
                        'countMessages' => $referal['countMessages']
                    ];
                }
            }
        }

        foreach ($userSubscribe as $item){
            if(!is_null($item)){
                if(!in_array($item, $contacts)){
                    $contacts[$item['id']] = [
                        'id' => $item['id'],
                        'first_name' => $item['first_name'],
                        'last_name' => $item['last_name'],
                        'image' => $item['image'],
                        'last_online_at' => $item['last_online_at'],
                        'lastMessage' => $item['lastMessage'],
                        'countMessages' => $item['countMessages']
                    ];
                }
            }
        }

        $blockedUsers = $user->chatBlockedUsers()->get();

        $blockedUsers->transform(function ($record) {
            $user = \App\Models\User::find($record->blocked_id);

            if($user){

                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'image' => $user->image ? '/storage/' . $user->image : '/image/avatar.png',
                    'last_online_at' => $user->last_online_at ? $user->last_online_at->format('Y-m-d H:i:s'): null,
                ];

            }else{
                return false;
            }


        });

        $filteredUsers = array_filter($contacts, function ($user) use ($blockedUsers) {
            foreach ($blockedUsers as $userToRemove) {
                if ($user['id'] === $userToRemove['id']) {
                    return false;
                }
            }
            return true;
        });

        return $filteredUsers;

    }

    public function changeFolderName(Request $request){

        $data = $request->all();

        $user = Auth::user();
        $dataToUpdate = [
            'folder_name' => htmlspecialchars($data['folder_name']),
            'sort' => $data['sort'],
            'users' => !empty($data['users']) ? implode(", ", $data['users']) : null
        ];


        $updateFolder = \App\Models\ChFolders::find($data['id']);

        if($updateFolder){

            $this->changeSortFolders($data['sort']);


            $updateFolder->sort = $dataToUpdate['sort'];
            $updateFolder->folder_name = $dataToUpdate['folder_name'];
            $updateFolder->users = $dataToUpdate['users'];

            if($updateFolder->save()){

                $userFolders = \App\Models\ChFolders::where('user_id', $user->id)->orderBy('sort', 'asc')->get();
                $userFolders->transform(function ($folder) {
                    $users = [];
                    if($folder->users != null && gettype($folder->users) == 'string'){
                        $userIds = explode(", ", $folder->users);
                        $userIds = array_map("intval", $userIds);
                        $contacts = $this->getUsersForChat();
                        $users = array_filter($contacts, function ($item) use ($userIds) {
                            return in_array($item['id'], $userIds);
                        });

                    }else{
                        $users = $this->getUsersForChat();
                    }

                    $countMessages = 0;

                    foreach($users as $user){
                        $countMessages += $user['countMessages'];
                    }

                    return [
                        'id' => $folder->id,
                        'folder_name' => $folder->folder_name,
                        'delete' => $folder->delete == 1,
                        'users' => $users,
                        'countMessages' => $countMessages,
                        'sort' => $folder->sort
                    ];

                });
                $getRecords = '';
                foreach ($userFolders as $folder){
                    $getRecords .= view('Chatify::layouts.listItem', [
                        'get' => 'userFolders',
                        'id' => $data['id'],
                        'folder' => $folder
                    ]);
                }
                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'addFolder'
                ]);
                $tabs = View::make('Chatify::layouts.listItem', [
                    'get' => 'userTab',
                    'folders' => $userFolders,
                    'id' => $data['id']
                ])->render();
                return response()->json([
                    'status' => true,
                    'folders' => $getRecords,
                    'tabs' => $tabs
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'folders' => []
                ]);
            }

        }
    }

    public function changeSortFolders($sort){

        DB::table('ch_folders')
            ->where('sort', '>=', $sort)
            ->where('user_id', Auth::user()->id)
            ->update(['sort' => DB::raw('sort + 1')]);

    }

    public function contactToMove(Request $request){

        $user = Auth::user();

        if($user){
            $folderToMove = \App\Models\ChFolders::find($request->folder_move_id);
            if($folderToMove){
                if($folderToMove->users != null){
                    $userIds = explode(", ", $folderToMove->users);
                    $userIds = array_map("intval", $userIds);
                    array_push($userIds, $request->userToMove);
                    $users = implode(', ', $userIds);
                    $folderToMove->users = $users;

                }else{
                    $folderToMove->users = $request->userToMove;
                }
                $resultToMove = $folderToMove->save();

            }

            $users = null;

            $folderFromMove = \App\Models\ChFolders::find($request->folder_from_id);
            if($folderFromMove){
                $users = explode(", ", $folderFromMove->users);

                $index = array_search($request->userToMove, $users);

                if ($index !== false) {
                    unset($users[$index]);
                }

                $newUsers = implode(", ", $users);
                $folderFromMove->users = $newUsers;
                $resultFromMove = $folderFromMove->save();
            }

            if(isset($resultFromMove) && $resultFromMove !=0 && isset($resultToMove) && $resultToMove != 0){

                return response()->json([
                    'status' => true
                ]);


            }
            else{
                return response()->json([
                    'status' => false,
                    'error' => 'Ошибка перемещения контакта'
                ]);

            }

        }

        return response()->json([
            'status' => false,
            'error' => 'Ошибка авторизации пользователя'
        ]);

    }

    public function getUserFolders(Request $request){

        $user = Auth::user();

        $data = $request->all();

//      Собираем контакты пользователя по умолчанию (рефералы и подписчики)
        $contacts = $this->getUsersForChat();

        $countMessages = 0;

        $userFolders = \App\Models\ChFolders::where('user_id', $user->id)->where('id', '!=', $data['id'])->get();

        //Если у пользователя еще нет папок, создаем первую по умолчанию с названием "Все" и закидываем туда всех пользователей (поле users == NULL)
        if($userFolders->count() == 0){

            $defaultFolder = \App\Models\ChFolders::create([
                'user_id' => $user->id,
                'folder_name' => 'Все',
                'delete' => false,
            ]);
            foreach ($contacts as $contact){
                $countMessages += $contact['countMessages'];
            }
            $userFolders = [
                'id' => $defaultFolder->id,
                'folder_name' => $defaultFolder->folder_name,
                'active' => true,
                'sort' => $defaultFolder->sort,
                'countMessages' => $countMessages,
                'delete' => $defaultFolder->delete,
                'users' => $contacts
            ];
            $userFolders = $userFolders->sortBy('sort')->values()->all();

        }else{
            $userFolders->transform(function ($folder) use ($contacts) {
                $usersData = [];
                if ($folder->users != null && gettype($folder->users) == 'string') {
                    $userIds = explode(", ", $folder["users"]);
                    $userIds = array_map("intval", $userIds);
                    $usersData = \App\Models\User::whereIn('id', $userIds)->get();
                    $usersData->transform(function ($user) {
                        $currentUser = Auth::user();
                        $lm = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', Auth::user()->id)->orderBy('created_at', 'desc')->get()->first();
                        if ($lm != null) {
                            $lastmessage = $lm->toArray();
                        } else {
                            $lastmessage = null;
                        }
                        return [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'image' => $user->image,
                            'last_online_at' => $user->last_online_at != null ? $user->last_online_at->format('d.m.Y H:m:s') : null,
                            'countMessages' => \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user->id)->where('seen', 0)->get()->count(),
                            'lastMessage' => $lastmessage,
                        ];

                    });

                }else if($folder->users == null){
                    $usersData = $contacts;
                }
                $countMessages = 0;
                foreach ($usersData as $item) {
                    $countMessages += \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $item['id'])->where('seen', 0)->get()->count();
                }

                return [
                    'id' => $folder['id'],
                    'folder_name' => $folder['folder_name'],
                    'active' => $folder['folder_name'] == 'Все',
                    'sort' => $folder['sort'],
                    'countMessages' => $countMessages,
                    'delete' => $folder['delete'],
//                    'users' => gettype($usersData) == 'Array'? $usersData : $usersData->toArray()

                ];


            })->sortBy('sort');

        }



        return response()->json([
            'status' => true,
            'folders' => $userFolders
        ]);

    }

    public function getDataDelFolder(Request $request){

        $folderToDel = \App\Models\ChFolders::find($request->id);

        if($folderToDel){
            $users = null;
            if($folderToDel->users != null){
                $usersIds = explode(', ', $folderToDel->users);
                $userIds = array_map("intval", $usersIds);
                $users = \App\Models\User::whereIn('id', $usersIds)->get();
                $users->transform(function ($user) {

                    $currentUser = Auth::user();
                    $lm = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user->id)->orderBy('created_at', 'desc')->get()->first();
                    if ($lm != null) {
                        $lastmessage = $lm->toArray();
                    } else {
                        $lastmessage = null;
                    }
                    return [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'image' => $user->image,
                        'last_online_at' => $user->last_online_at != null ? $user->last_online_at->format('d.m.Y H:m:s') : null,
                        'countMessages' => \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user->id)->where('seen', 0)->get()->count(),
                        'lastMessage' => $lastmessage,
                    ];

                });
            }

            $countMessages = 0;

            if($users != null){
                foreach ($users as $user){
                    $countMessages += \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user['id'])->where('seen', 0)->get()->count();
                }
            }



            $folder = [

                'id' => $folderToDel->id,
                'folder_name' => $folderToDel->folder_name,
                'active' => $folderToDel->folder_name == 'Все',
                'sort' => $folderToDel->sort,
                'countMessages' => $countMessages,
                'delete' => $folderToDel->delete,
                'users' => $users

            ];

            $userFolders = \App\Models\ChFolders::where('id', '<>', $folder['id'])->where('user_id', Auth::user()->id)->where('delete', 1)->get();
            $userFolders->transform(function($folder){
                $usersData = [];
                if ($folder->users != null && gettype($folder->users) == 'string'){
                    $userIds = explode(", ", $folder["users"]);
                    $userIds = array_map("intval", $userIds);
                    $usersData = \App\Models\User::whereIn('id', $userIds)->get();
                    $usersData->transform(function ($user) {
                        $currentUser = Auth::user();
                        $lm = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user->id)->orderBy('created_at', 'desc')->get()->first();
                        if ($lm != null) {
                            $lastmessage = $lm->toArray();
                        } else {
                            $lastmessage = null;
                        }
                        return [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'image' => $user->image,
                            'last_online_at' => $user->last_online_at != null ? $user->last_online_at->format('d.m.Y H:m:s') : null,
                            'countMessages' => \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user->id)->where('seen', 0)->get()->count(),
                            'lastMessage' => $lastmessage,
                        ];

                    });
                }else{
                    $usersData = $this->getUsersForChat();
                }
                $countMessages = 0;
                foreach ($usersData as $item) {
                    $countMessages += \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $item['id'])->where('seen', 0)->get()->count();
                }

                return [
                    'id' => $folder['id'],
                    'folder_name' => $folder['folder_name'],
                    'active' => $folder['folder_name'] == 'Все',
                    'sort' => $folder['sort'],
                    'countMessages' => $countMessages,
                    'delete' => $folder['delete'],
                    'users' => gettype($usersData) == 'array'? $usersData : $usersData->toArray()

                ];

            });

            return response()->json([
                'status' => true,
                'folder' => $folder,
                'user_folders' => $userFolders
            ]);

        }else{
            return response()->json([
                'status' => false,
                'error' => 'Папка для удаления не найдена в базе. Обновите страницу и попробуйте еще раз.'
            ]);
        }



    }

    public function deteleFolder(Request $request){

        if($request->folder_id != null){

            $folder = \App\Models\ChFolders::find($request->id);
            $userIds = explode(", ", $folder->users);

            $folderToMove = \App\Models\ChFolders::find($request->folder_id);

            if($folderToMove->users != null){
                $users = explode(', ', $folderToMove->users);
                $newUsers = array_merge($users, $userIds);
                $newUsers = array_unique($newUsers);
                $newUsers = implode(', ', $newUsers);
                $folderToMove->users = $newUsers;

            }else{
                $folderToMove->users = $folder->users;
            }
            $folderToMove->save();
            $folder = \App\Models\ChFolders::find($request->id);
            $folder->delete();

//            $userFolders = \App\Models\ChFolders::where('user_id', Auth::user()->id)->get();
//            $userTabs = View::make('Chatify::layouts.tab', ['folders' => $userFolders, 'id' => 0])->render();
//            $userFolders = View::make('Chatify::layouts.userFolders', ['folders' => $userFolders])->render();



            return response()->json(['status' => true, 'message' => 'Запись успешно удалена'], 200);
        }else{
            $folder = \App\Models\ChFolders::find($request->id);
            $folder->delete();
            return response()->json(['status' => true, 'message' => 'Запись успешно удалена'], 200);
        }

    }

    public function blockUser(Request $request){

        $user = Auth::user();

        $complaint = \App\Models\Complain::create([
            'user_id' => Auth::user()->id,
            'object_type' => 0,
            'object_id' => $request->user_id,
            'request' => $request->reason
        ]);

        $user_blocked = false;

        if($request->del_conv == "true"){


            \App\Models\ChMessage::where('from_id', Auth::user()->id)->where('to_id', $request->user_id)->where('delete_from', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_from' => 1]);
            \App\Models\ChMessage::where('from_id', Auth::user()->id)->where('to_id', $request->user_id)->where('delete_from', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_to' => 1]);
            \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $request->user_id)->where('delete_to', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_to' => 1]);
            \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $request->user_id)->where('delete_to', 0)->where('created_at','<', Carbon::now())->orderBy('created_at', 'asc')->update(['delete_from' => 1]);

            $record = \App\Models\ChBlockedUsers::where('user_id', $user->id)->where('blocked_id', $request->user_id)->first();

            if(!$record){

                $del_conv = \App\Models\ChBlockedUsers::create([

                    'user_id' => $user->id,
                    'blocked_id' => $request->user_id,
                    'reason' => $request->reason

                ]);

            }
            $user_blocked = true;
        }

        if($complaint){
            return response()->json([
                'status' => true,
                'reload' => (bool)$user_blocked,
                'complaint' => $user_blocked ? 'Жалоба отправлена администратору. Пользователь заблокирован' : 'Жалоба отправлена администратору'
            ]);
        }
        return response()->json([
            'status' => false,
            'compliant' => 'Ошибка отправки жалобы'
        ]);



    }

    public function setReaction(Request $request){

        //TODO Уточнить у клиента ограничение на реакции
        $record = \App\Models\ChUserReaction::where('user_id', Auth::user()->id)->where('ch_message_id', $request->msgId)->first();
        if($record != null){
            $record->update([
                'reaction' => $request->reaction
            ]);
        }else{
            $record = \App\Models\ChUserReaction::create([
                'ch_message_id' => $request->msgId,
                'user_id' => Auth::user()->id,
                'reaction' => $request->reaction
            ]);
        }

        if($record){
            return response()->json([
                'status' => true,
                'reaction' => $record->reaction,
                'msgId' => $request->msgId
            ]);
        }

        return response()->json([
            'status' => false
        ]);


    }

    public function deleteMessage(Request $request){

        $message = \App\Models\ChMessage::find($request->message_id);

        if($message){
            $direction = null;
            if($message->from_id == Auth::user()->id){
                $message->hide_from = 1;
                $direction = 'sent';
            }else{
                $message->hide_to = 1;
                $direction = 'reseived';
            }
            $record = $message->save();

            if($record){

                $messageData = [
                    'id' => $message->id,
                    'direction' => $direction,
                    'dateTitle' => $message->created_at->format('Y-m-d'),
                    'message' => '<p>Удаленное сообщение</p>',
                    'date' => $message->created_at->format('H:i'),
                    'msgId' => $request->message_id,
                    'reply' => $message->reply_id,
                    'reaction' => null,
                    'delete' => true,
                    'forwarded' => $message->forwarded
                ];
                $messages[] = $messageData;

                $view = View::make('Chatify::layouts.listItem', ['get' => 'messageList', 'messages'=> $messages])->render();

                return response()->json([
                    'status' => true,
                    'message' => $view,
                    'message_id' => $request->message_id
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Ошибка удаления сообщения'
                ]);

            }


        }else{
            return response()->json([
                'status' => false,
                'message' => 'Сообщение не найдено'
            ]);

        }


    }

    public function getUsersToForward(Request $request){

        $contacts = $this->getUsersForChat();
        $getRecords = '';
        foreach ($contacts as $user){

            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'userForModal',
                'checked' => false,
                'user' => $user

            ]);

        }


        if($contacts){
            return response()->json([
                'status' => true,
                'contacts' => $getRecords
            ]);

        }else{
            return response()->json([
                'status' => false,
                'contacts' => [],
                'error' => 'Ошибка получения данных'
            ]);

        }

    }

    public function forwardMessage(Request $request){

        $currentUser = Auth::user();
        $usersIds = array_map("intval", $request->users);
        $users = \App\Models\User::whereIn('id', $usersIds)->get();

        $message = \App\Models\ChMessage::find($request->message_id);

        if($users){
            if($message){
                $messages = [];
                foreach ($users as $user){

                    $record = \App\Models\ChMessage::create([
                        'from_id' => $currentUser->id,
                        'to_id' => $user->id,
                        'body' => $message->body,
                        'attachment' => $message->attachment,
                        'audio_file' => $message->audio_file,
                        'forwarded' => true,
                        'forward_from' => $request->forward_from
                    ]);

                }

                return response()->json([
                    'status' => true,
                    'message' => 'Сообщение переслано',
                    'users' => $users
                ]);


            }else{
                return response()->json([
                    'status' => false,
                    'error' => 'Сщщбщение для пересылки не найдено'
                ]);
            }

        }

    }

    public function countMessages(Request $request): \Illuminate\Http\JsonResponse
    {

        $countMessages = 0;
        $users = $this->getUsersForChat();

        foreach ($users as $user){
            if($user['countMessages'] > 0 ){
                $countMessages++;
            }
        }

        return response()->json([
            'status' => true,
            'countMessages' => $countMessages
        ]);

    }

    public function searchUser(Request $request){

        $getRecords = null;
        $input = trim(filter_var($request['input']));

        $usersList = $this->getUsersForChat();

        $filteredUsers = array();
        foreach ($usersList as $user) {

            if (strpos($user["first_name"], $input) !== false || strpos($user["last_name"], $input) !== false) {
                $unreadMessages = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user['id'])->where('seen', 0)->get()->count();
                $filteredUsers[] = array_merge($user, ['unreadMessages' => $unreadMessages]);
            }
        }

        foreach($filteredUsers as $user){
            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'find_user',
                'user' => $user

            ]);
        }

        if(count($filteredUsers) < 1){
            $getRecords = '<p style="align-self: center;"><span>Ничего не найдено</span></p>';
        }

        return response()->json([
            'records' => $getRecords
        ], 200);

    }

    public function getUsersToCreate(Request $request){

        $contacts = $this->getUsersForChat();

        $getRecords = '';

        foreach($contacts as $user){

            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'userForModal',
                'checked' => false,
                'user' => $user

            ]);

        }


        if($contacts){
            return response()->json([
                'status' => true,
                'contacts' => $getRecords
            ]);

        }else{
            return response()->json([
                'status' => false,
                'error' => 'В вашем списке нет контактов'
            ]);
        }

    }

    public function reloadUsers(Request $request){

        if($request->folderId == 0){

            $contacts = $this->getUsersForChat();
            $getRecords = '';
            foreach ($contacts as $user){
                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'userForModal',
                    'user' => $user

                ]);
            }

            return response()->json([
                'status' => true,
                'contacts' => $getRecords
            ]);

        }else{
            $folder = \App\Models\ChFolders::find($request->folderId);

            if($folder){
                $contacts = [];
                if($folder->users != null){
                    $usersId = explode(", ", $folder->users);
                    $userIds = array_map("intval", $usersId);
                    $contacts = \App\Models\User::whereIn('id', $userIds)->get();
                    $contacts->transform(function ($user) {
                        $currentUser = Auth::user();
                        $lm = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user->id)->orderBy('created_at', 'desc')->get()->first();
                        if ($lm != null) {
                            $from_id = \App\Models\User::find($lm->from_id);
                            if($from_id){
                                $from_id = [
                                    'id' => $from_id->id,
                                    'first_name' => $from_id->first_name,
                                    'last_name' => $from_id->last_name,
                                    'image' => $from_id->image ? '/storage/' . $from_id->image : '/image/avatar.png',
                                    'last_online_at' => $from_id->last_online_at ? $from_id->last_online_at->format('Y-m-d H:i:s'): null,
                                ];
                            }
                            if($lm->from_id == Auth::user()->id){
                                $messageBody = $lm->hide_from == 1 ? 'Удаленное сообщение' : $lm->body;
                                $image = Auth::user()->image;
                            }else{
                                $messageBody = $lm->hide_to == 1 ? 'Удаленное сообщение' : $lm->body;
                                $image = \App\Models\User::find($lm->from_id)->image;
                            }
                            $lastmessage = [
                                'id' => $lm->id,
                                'from_id' => $from_id,
                                'body' => $messageBody,
                                'attachment' => $lm->attachment,
                                'audio' => $lm->audio_file,
                                'created_at' => $lm->created_at->format('Y-m-d H:i:s'),
                                'image' => $image
                            ];
                        } else {
                            $lastmessage = null;
                        }
                        return [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'image' => $user->image ? '/storage/'.$user->image : '/image/avatar.png',
                            'last_online_at' => $user->last_online_at != null ? $user->last_online_at->format('d.m.Y H:m:s') : null,
                            'countMessages' => \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $user->id)->where('seen', 0)->get()->count(),
                            'lastMessage' => $lastmessage,
                        ];

                    });

                }else{
                    $contacts = $this->getUsersForChat();
                }
                $getRecords = '';

                foreach($contacts as $user){

                    $getRecords .= view('Chatify::layouts.listItem', [
                        'get' => 'listUsers',
                        'user' => $user,
                        'id' => $request->user_id

                    ]);

                }

                return response()->json([
                    'status' => true,
                    'contacts' => $getRecords
                ]);


            }
        }


    }

    public function findUser(Request $request){

        $searchTerm = strtolower($request->searchText);

        $contacts = $this->getUsersForChat();

        $filteredArray = array_filter($contacts, function ($item) use ($searchTerm) {
            return strpos($item['first_name'], $searchTerm) !== false || strpos($item['last_name'], $searchTerm) !== false;
        });

        $getRecords = '';
        foreach ($filteredArray as $user){
            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'userForModal',
                'checked' => false,
                'user' => $user

            ]);
        }

        if(empty($getRecords)){
            $getRecords = '<p>Ничего не найдено</p>';
        }

        return response()->json([
            'status' => true,
            'contacts' => $getRecords
        ]);

    }

    public function copyMessage(Request $request){

        $message = \App\Models\ChMessage::find($request->message_id);
        if($message){
            $copyText = $message->body;
            $type = 'text';
            $error = false;
            if ($message->attachment !== null) {
                $attachmentData = json_decode($message->attachment);
                $copyText = "/storage/attachments/{$attachmentData->new_name}";
                $type = 'file';
            } elseif ($message->audio_file !== null) {
                $copyText = "/storage/audio/{$message->audio_file}";
                $type = 'file';
            } elseif ($message->from_id == Auth::user()->id) {
                $copyText = $message->hide_from == 1 ? 'Удаленное сообщение' : $message->body;
                if($copyText != $message->body){
                    $error = true;
                }
            } else {
                $copyText = $message->hide_to == 1 ? 'Удаленное сообщение' : $message->body;
                if($copyText != $message->body){
                    $error = true;
                }
            }

            if($error){
                return response()->json([
                    'status' => false,
                    'error' => 'Сообщение удалено',
                ]);

            }else{
                return response()->json([
                    'status' => true,
                    'type' => $type,
                    'copyText' => $copyText
                ]);
            }

        }else{
            return response()->json([
                'status' => false,
                'error' => 'Сообщение в базе не найдено'
            ]);

        }


    }

    public function refreshTab(Request $request){

        $user = Auth::user();

        $folder = $user->chatFolders()->where('id', $request->tabId)->first();
        $getRecords = '';
        $userInvites = '';
        if($folder){
            if($folder->folder_name == 'Все'){

                $contacts = $this->getUsersForChat();
                $blockedUsers = \App\Models\ChBlockedUsers::where('user_id', Auth::user()->id)->get();
                $usersIds = [];

                $filteredUsers = array_filter($contacts, function ($user) use ($usersIds) {
                    return !in_array($user["id"], $usersIds);
                });


                $sortedArray = collect($filteredUsers)->sortBy(function ($item) {
                    return optional($item['lastMessage'])['created_at'];
                })->all();

                usort($sortedArray, function ($a, $b) {
                    if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                        return 0;
                    } elseif ($a['lastMessage'] === null) {
                        return 1;
                    } elseif ($b['lastMessage'] === null) {
                        return -1;
                    }
                    return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                });


                foreach( $sortedArray as $contact){

                    $getRecords .= view('Chatify::layouts.listItem', [
                        'get' => 'listUsers',
                        'id' => $request->user_id ?? 0,
                        'user' => $contact
                    ]);
                }

                //групповые чаты
                $userInvites = $this->getInvites();
                $groupChats = $this->getUserGroupChat();




            }else{

                $usersIds = explode(', ', $folder->users);
                $usersIds = array_map("intval", $usersIds);
                $contacts = $this->getUsersForChat();
                $blockedUsersRecords = \App\Models\ChBlockedUsers::where('user_id', Auth::user()->id)->get();
                $blockedUsers = [];
                foreach ($blockedUsersRecords as $record){
                    array_push($blockedUsers, $record->blocked_id);
                }
                foreach($usersIds as $key=>$record){
                    if(in_array($record, $blockedUsers)){
                        unset($usersIds[$key]);
                    }
                }
                $filteredUsers = array_filter($contacts, function ($user) use ($usersIds) {
                    return in_array($user["id"], $usersIds);
                });

                $sortedArray = collect($filteredUsers)->sortBy(function ($item) {
                    return optional($item['lastMessage'])['created_at'];
                })->all();

                usort($sortedArray, function ($a, $b) {
                    if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                        return 0;
                    } elseif ($a['lastMessage'] === null) {
                        return 1;
                    } elseif ($b['lastMessage'] === null) {
                        return -1;
                    }
                    return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                });

                foreach ($sortedArray as $filteredUser){
                    $getRecords .= view('Chatify::layouts.listItem', [
                        'get' => 'listUsers',
                        'id' => $request->user_id ?? 0,
                        'user' => $filteredUser
                    ]);
                }

                if(!empty($folder->group_id)){
                    $userGroupChats = explode(',', $folder->group_id);
                    $userGroupChats = array_map("intval", $userGroupChats);
                    $groupChats = $this->getUserGroupChat();

                    $groupChats = array_filter($groupChats, function ($chat) use ($userGroupChats) {
                        return in_array($chat["id"], $userGroupChats);
                    });



                }


            }

            $folders = $this->getFolders($user->id, $request->tabId);

            $unreadMessages = 0;
            $contacts = $this->getUsersForChat();
            foreach($contacts as $contact){
                if($contact['countMessages'] > 0){
                    $unreadMessages++;
                }
            }

            $chatRecord = '';

            foreach ($groupChats as $chat){
                $chatRecord .= view('Chatify::layouts.listItem', [
                    'get' => 'listGroups',
                    'group' => $chat,
                    'id' => 0
                ]);
            }

            $userMenu = view('Chatify::layouts.listItem', [
                'get' => 'userMenu',
                'userId' => Auth::user()->id,
                'countMessages' => $unreadMessages
            ])->render();


            return response()->json([
                'status' => true,
                'contacts' => $getRecords,
                'folders' => $folders,
                'countMessages' => $unreadMessages,
                'userMenu' => $userMenu
            ]);


        }

    }

    public function getFolders($user_id, $folder_id){

        $user = \App\Models\User::find($user_id);

        $userFolders = $user->chatFolders()->orderBy('sort', 'asc')->get();

        $userFolders->transform(function ($folder) {
            $users = null;
            $unreadMessages = 0;
            $contacts = $this->getUsersForChat();
            if(!$folder->users){
                if($folder->folder_name != 'Личное'){
                    $folderUsers = $contacts;
                    foreach($contacts as $contact){
                        $unreadMessages += $contact['countMessages'];
                    }
                }
            }else{
                $users = explode(', ', $folder->users);
                $users = array_map('intval', $users);
                $users = array_filter($contacts, function ($item) use ($users) {
                    return in_array($item['id'], $users);
                });
                foreach ($users as $user){
                    $unreadMessages += $user['countMessages'];
                }
            }

            return [
                'id' => $folder->id,
                'folder_name' => $folder->folder_name,
                'countMessages' => $unreadMessages,
                'sort' => $folder->sort,
                'delete' => $folder->delete
            ];
        });

        $getRecords = '';
        foreach ($userFolders as $folder){
            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'userFolders',
                'id' => $folder_id,
                'folder' => $folder
            ]);

        }

        $getRecords .= view('Chatify::layouts.listItem',[
            'get' => 'addFolder'
        ]);


        return $getRecords;

    }

    public function getUsersFromFolder($folderId, $user_id){
        $user = Auth::user();

        $folder = $user->chatFolders()->where('id', $folderId)->first();

        if(is_null($folder->users)){

            $contacts = $this->getUsersForChat();

        }else{

            $usersIds = explode(', ', $folder->users);
            $usersIds = array_map('intval', $usersIds);
            $contacts = \App\Models\User::whereIn('id', $usersIds)->get();
            $contacts->transform(function ($contact) {

                $countUnread = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $contact->id)->where('seen', 0)->get()->count();

                $last_message = null;
                $lm = \App\Models\ChMessage::where('to_id', Auth::user()->id)->where('from_id', $contact->id)
                    ->orWhere('to_id', $contact->id)->where('from_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
                if($lm){
                    $from_id = \App\Models\User::find($lm->from_id);
                    if($from_id){
                        $from_id = [
                            'id' => $from_id->id,
                            'first_name' => $from_id->first_name,
                            'last_name' => $from_id->last_name,
                            'image' => $from_id->image ? '/storage/' . $from_id->image : '/image/avatar.png',
                            'last_online_at' => $from_id->last_online_at ? $from_id->last_online_at->format('Y-m-d H:i:s') : null
                        ];
                    }
                    if($lm->from_id == Auth::user()->id){
                        $body = $lm->delete_from ? 'Удаленное сообщение' : $lm->body;
                        $attachment = $lm->delete_from ? 'Удаленное сообщение' : $lm->attachment;
                        $audio = $lm->delete_from ? 'Удаленное сообщение' : $lm->audio_file;
                    }else{
                        $body = $lm->delete_to ? 'Удаленное сообщение' : $lm->body;
                        $attachment = $lm->delete_to ? 'Удаленное сообщение' : $lm->attachment;
                        $audio = $lm->delete_to ? 'Удаленное сообщение' : $lm->audio_file;
                    }

                    $last_message = [
                        'id' => $lm->id,
                        'from_id' => $from_id,
                        'body' => $body,
                        'attachment' => $attachment,
                        'audio' => $audio,
                        'seen' => $lm->seen,
                        'created_at' => $lm->created_at->format('Y-m-d H:i:s')
                    ];
                }

                return [
                    'id' => $contact['id'],
                    'first_name' => $contact['first_name'],
                    'last_name' => $contact['last_name'],
                    'image' => $contact['image'] ? '/storage/'. $contact['image'] : '/image/avatar.png',
                    'last_online_at' => $contact['last_online_at'],
                    'lastMessage' => $last_message,
                    'countMessages' => $countUnread
                ];


            });

        }
        $getRecords = '';
        foreach ($contacts as $contact){

            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'listUsers',
                'id' => $user_id,
                'user' => $contact
            ]);
        }

        return $getRecords;



    }

    public function readMessages(Request $request){

        $user = Auth::user();

        $unReadMessages = \App\Models\ChMessage::where('to_id', $user->id)->where('from_id', $request->user_id)->where('seen', 0)->update(['seen' => 1]);

        $folders = $this->getFolders(Auth::user()->id, $request->folder_id);

        return response()->json([
            'status' => true,
            'userFolders' => $folders,
        ]);


    }

    public function setUserSettings(Request $request){

        $record = \App\Models\ChUsersSettings::where('user_id', Auth::user()->id)
            ->where('setting_user_id', $request->user_id)
            ->first();
        if($record){
            $columnName = $request->name;
            $newValue = $request->value == 'true' ? 1 : 0;
            $record->update([$columnName => $newValue]);
        }
        $folders = $this->getFolders(Auth::user()->id, $request->folder_id);

        return response()->json([
            'status' => true,
            'userFolders' => $folders
        ]);

    }

    public function getFileType($file)
    {

        $filename = $file;

        $result = '';
        $parts = explode('.', $filename); // Разделяем имя файла и расширение
        $originalName = $parts[0];


        if ($filename) {
            $fileDirectory = '/storage/attachments/';
            $filePath = $fileDirectory . $filename;
            $publicPath = public_path($filePath);


            if (file_exists($publicPath)) {
                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                $playableExtensions = ['mp4', 'mov', 'avi', 'mpegps', 'wmv'];
                $audio = ['wav', 'mp3'];
                $images = ['png', 'jpg', 'jpeg', 'gif'];
                if (in_array(strtolower($extension), $playableExtensions)) {
                    $result = '<video controls width="640" height="360">
                      <source src="' . $filePath . '" type="video/mp4">
                      <source src="' . $filePath . '" type="video/webm">
                      Your browser does not support the video tag.
                  </video>';
                } else if (in_array(strtolower($extension), $audio)) {
                    if ($extension == 'wav') {
                        $result = '<audio controls><source src="'.$filePath.'" type="audio/wave">Ваш браузер не поддерживает аудиофайлы WebM.</audio>';
                    } else {
                        $result = '<audio controls><source src="'.$filePath.'" type="audio/mpeg">Ваш браузер не поддерживает аудиофайлы WebM.</audio>';
                    }
                } else if (in_array(strtolower($extension), $images)) {
                    $result = "<img src='{$filePath}'>";
                } else {
                    $result = '<a href="'.$filePath.'">Скачать '.$originalName.'</a>';
//                    dd($result);
                }

            } else {
                $result = "Файл не найден.";
            }
//            dd($result);
            return $result;


        }
    }

    public function getUserInformation(Request $request){

        $user = \App\Models\User::where('id', $request->id)->first();

        if($user){

            $userInfo = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'last_online_at' => $user->last_online_at ? $user->last_online_at->format('Y-m-d H:i:s'): null
            ];


            return response()->json([
                'status' => true,
                'userInfo' => $userInfo
            ]);


        }else{

            return response()->json([
                'status' => false,
                'error' => 'error'
            ]);

        }




    }

    public function getImageFromAttachment($attachment){

        $result = null;
        if($attachment != null){
            $attachmentData = json_decode($attachment);
            $filename = $attachmentData->new_name;

            $fileDirectory = '/storage/attachments/';
            $arrayImages = ['png', 'jpg', 'jpeg', 'gif'];
            $filePath = $fileDirectory . $filename;
            $publicPath = public_path($filePath);
            if (file_exists($publicPath)) {

                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $arrayImages)) {
                    $result = "<img src='{$filePath}'>";
                }else{
                    $result = null;
                }
            }
        }
        return $result;
    }

    public function updateContacts(Request $request){

        $userFolders = \App\Models\ChFolders::where('user_id', Auth::user()->id)->orderBy('sort', 'asc')->get();
        $contacts = $this->getUsersForChat();

        if($userFolders){

            $userFolders->transform(function ($folder) use ($contacts){

                if($folder->users != null){
                    $usersIds = explode(", ", $folder->users);
                    $usersIds = array_map("intval", $usersIds);
                    $users = array_filter($contacts, function ($item) use ($usersIds) {
                        return in_array($item['id'], $usersIds);
                    });
                    usort($users, function ($a, $b) {
                        if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                            return 0;
                        } elseif ($a['lastMessage'] === null) {
                            return 1;
                        } elseif ($b['lastMessage'] === null) {
                            return -1;
                        }
                        return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                    });

                }else{
                    $users = $contacts;
                    usort($users, function ($a, $b) {
                        if ($a['lastMessage'] === null && $b['lastMessage'] === null) {
                            return 0;
                        } elseif ($a['lastMessage'] === null) {
                            return 1;
                        } elseif ($b['lastMessage'] === null) {
                            return -1;
                        }
                        return $b['lastMessage']['created_at'] <=> $a['lastMessage']['created_at'];
                    });

                }

                $countMessages = 0;

                foreach($users as $user){
                    if($user['countMessages'] > 0){
                        $countMessages++;
                    }
                }

                return [
                    'id' => $folder->id,
                    'folder_name' => $folder->folder_name,
                    'delete' => $folder->delete == 1,
                    'users' => $users,
                    'countMessages' => $countMessages
                ];

            });

        }

        $folders = '';

        foreach ($userFolders as $folder){
            $folders .= view('Chatify::layouts.listItem', [
                'get' => 'userFolders',
                'folder' => $folder,
                'user_id' => $request->user_id,
                'id' => $request->folder_id
            ])->render();
        }
        $folders .= view('Chatify::layouts.listItem', ['get' => 'addFolder']);


        $tabs = '';

        $tabs = view('Chatify::layouts.listItem', [
            'get' => 'userTab',
            'folders' => $userFolders,
            'id' => $request->folder_id,
            'user_id' => $request->user_id
        ])->render();

        $countMessages = 0;

        foreach($contacts as $contact){
            if($contact['countMessages'] > 0){
                $countMessages++;
            }
        }

        $userMenu = view('Chatify::layouts.listItem', [
            'get' => 'userMenu',
            'userId' => Auth::user()->id,
            'countMessages' => $countMessages
        ])->render();


        return response()->json([
            'status' => true,
            'folders' => $folders,
            'tabs' => $tabs,
            'userMenu' => $userMenu
        ]);

    }

    private function formatDateToObject($dateString) {
        $messageDate = Carbon::parse($dateString);
        $now = Carbon::now();

        if ($messageDate->isSameDay($now)) {
            return ['date' => 'Сегодня', 'time' => $messageDate->format('H:i')];
        } elseif ($messageDate->isYesterday()) {
            return ['date' => 'Вчера', 'time' => $messageDate->format('H:i')];
        } else {
            return ['date' => explode(' ', $dateString)[0], 'time' => $messageDate->format('H:i')];
        }
    }

    public function addGroupChat(\Illuminate\Http\Request $request){

        $filePath = '';
        $description = '';
        $image = $request->file('image');

        if(isset($image) == true && empty($image) == false){
            $filePath = $image->path();
        }

        if(isset($request['description']) == true && !is_null($request['description'])){
            $description = $request['description'];
        }


        $record = \App\Models\ChGroup::create([

            'name' => $request['chat_name'],
            'image' => $filePath != '' ? $filePath : null,
            'owner' => Auth::user()->id,
            'description' => $description,
            'chat' => true,
            'type' => $request['chat_type'],
            'slug' => $request['chat_type'] == 'public' && $request['slug'] ? $request['slug'] : Str::slug($request['chat_name'])

        ]);

        if($record){

            $contacts = $this->getUsersForChat();

            $getRecords = '';

            foreach($contacts as $user){

                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'userForModal',
                    'checked' => false,
                    'user' => $user

                ]);

            }
            if($contacts){
                return response()->json([
                    'status' => true,
                    'chat_id' => $record->id,
                    'contacts' => $getRecords
                ]);

            }else{
                return response()->json([
                    'status' => false,
                    'error' => 'В вашем списке нет контактов'
                ]);
            }

        }else{
            return response()->json([
                'status' => false,
                'error' => 'Ошибка создания чата'
            ]);
        }

    }

    public function createInviteGroupchat(\Illuminate\Http\Request $request){

        $request_data = $request->all();

        if(isset($request_data['users']) == true){

            $chatModel = \App\Models\ChGroup::where('id', $request_data['chat_id'])->first();

            if($chatModel){
                foreach ($request_data['users'] as $userId => $value){
                    $haveRecord = \App\Models\ChGroupInvite::where('ch_group_id', $chatModel->id)->where('user_id', $value)->first();
                    if(!$haveRecord){
                        $chatModel->invites()->create([
                            'ch_group_id' => $chatModel->id,
                            'user_id' => $value,
                            'seen' => 0
                        ]);
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Приглашения отправлены'
                ]);
            }
        }




    }

    public function getChatInfo(\Illuminate\Http\Request $request){

        $chat = \App\Models\ChGroup::where('id', $request->chat_id)->first();

        $chatInfo = [];

        if($chat){

            $chatMessages = $chat->messages()->get();
            $groupedMessages = '';
            if($chatMessages){

                $groupedMessages = $chatMessages->groupBy(function ($message) {
                    return $message->created_at->format('Y-m-d');
                });
                $groupedMessages->transform(function ($messages, $date) {

                    return $messages->map(function ($message) {
                        $messageBody = $message->body;

                        $isFile = false;

                        if (!$messageBody) {
                            if ($message->attachments) {
                                $attachmentData = json_decode($message->attachments);
                                $messageBody = $this->getFileType($attachmentData->new_name);
                                $isFile = true;
                            } elseif ($message->audio_file){
                                $messageBody = html_entity_decode("<audio controls><source src='/storage/audio/{$message->audio_file}' type='audio/webm'>Ваш браузер не поддерживает аудиофайлы WebM.</audio>");
                                $isFile = true;
                            }
                        }
                        $forwardUser = null;
                        //TODO уточнить момент по реакциям в групповых чатах и каналах. Пока прикручена тот же функционал, что и в личной переписке
                        $reactions = \App\Models\ChUserReaction::where('ch_message_id', $message->id)->where('user_id', Auth::user()->id)->get();

                        $emojies = array();
                        if($reactions){
                            foreach($reactions as $reaction){
                                array_push($emojies, $reaction->reaction);
                            }
                        }else{
                            $emojies = null;
                        }

                        $messageDelete = false;
                        $reply = [];
                        $senderInfo = \App\Models\User::where('id', $message->user_id)->first();
                        $messageUserData = [];
                        if($senderInfo){
                            $messageUserData = [
                                'id' => $senderInfo->id,
                                'first_name' => $senderInfo->first_name,
                                'last_name' => $senderInfo->last_name,
                                'image' => $senderInfo->image ? '/storage/' . $senderInfo->image : '/image/avatar.png'
                            ];

                        }
                        return [
                            'id' => $message->id,
                            'direction' => $message->user_id == Auth::user()->id ? 'sent' : 'reseived',
                            'from_id' => $message->user_id,
                            'messageUserData' => $messageUserData,
                            'delete' => $messageDelete,
                            'isFile' => $isFile,
                            'message' => $messageBody,
                            'date' => $message->created_at->format('H:i'),
                            'reply' => $reply,
                            'reaction' => $emojies,
                            'forwarded' => !($message->forwarded == 0),
                            'forward_from' => $forwardUser
                        ];


                    });
                });
                $getRecords = '';
                if($groupedMessages){
                    foreach($groupedMessages as $key => $message){
                        $date = $this->formatDateToObject($key);
                        $getRecords .= view('Chatify::layouts.listItem', [
                            'get' => 'messageListChat',
                            'key' => $date['date'],
                            'messages' => $message
                        ]);
                    }
                }
            }


            $chatUsers = $chat->chatUsers()->get()->count();

            $chatInfo = [
                'id' => $chat->id,
                'name' => $chat->name,
                'chat' => $chat->chat == 1,
                'image' => ($chat->image ? '/storage/'. $chat->image : ($chat->chat == 1 ? '/image/groupchat.png' : '/image/channel.png')),
                'messages' => $getRecords,
                'countUsers' => $chatUsers
            ];

            return response()->json([
                'status' => true,
                'chatInfo' => $chatInfo
            ]);


        }else{
            return response()->json([
                'status' => false,
                'error' => 'Чат не найден'
            ]);
        }




    }

    public function getInviteList(\Illuminate\Http\Request $request){

        $curentUser = Auth::user();


        $getRecords = '';

        if($curentUser){

            $inviteList = \App\Models\ChGroupInvite::where('user_id', $curentUser->id)->where('seen', 0)->get();

            if($inviteList){

                $inviteList->transform(function ($invite) {

                    $chat = \App\Models\ChGroup::where('id', $invite->ch_group_id)->first();

                    $invited = \App\Models\User::where('id', $invite->sender_id)->first();

                    if($invited){

                        $invited = [
                            'id' => $invited->id,
                            'first_name' => $invited->first_name,
                            'last_name' => $invited->last_name,
                            'image' => $invited->image ? '/storage/' . $invited->image : '/image/avatar.png',
                            'last_online_at' => $invited->last_online_at ? $invited->last_online_at->format('Y-m-d H:i:s') : null
                        ];

                    }


                    return [
                        'id' => $chat->id,
                        'invite_name' => $chat->name,
                        'image' => ($chat->image ? '/storage/'. $chat->image : ($chat->chat == 1 ? '/image/groupchat.png' : '/image/channel.png')),
                        'invited' => $invited
                    ];


                });

                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'listInvites',
                    'invites' => $inviteList
                ]);

                $headerInvite = view('Chatify::layouts.listItem', [
                    'get' => 'headerInvite'
                ])->render();

            }

        }

        if($getRecords != ''){
            return response()->json([
                'status' => true,
                'inviteList' => $getRecords,
                'header' => $headerInvite
            ]);

        }else{
            return response()->json([
                'status' => true,
                'inviteList' => 'Список приглашений пуст'
            ]);
        }
    }

    public function removeInvite(\Illuminate\Http\Request $request){

        $currentUser = Auth::user();

        if($currentUser){

            $request_data = $request->all();

            $invite = \App\Models\ChGroupInvite::where('user_id', $currentUser->id)->where('ch_group_id', $request_data['chat_id'])->where('seen', 0)->first();

            if($invite){

                $invite->seen = 1;


                if($invite->save()){

                    return response()->json([
                        'status' => true,
                    ]);
                }else{
                    return response()->json([
                        'status' => false
                    ]);
                }
            }else{
                return response()->json([
                    'status' => false
                ]);
            }

        }else{

            return response()->json([
                'status' => false,
                'error' => 'Пользователь не авторизован'
            ]);
        }



    }

    public function aproveInvite(\Illuminate\Http\Request $request){

        $request_data = $request->all();
        $currentUser = Auth::user();

        if($currentUser){

            $invite = \App\Models\ChGroupInvite::where('user_id', $currentUser->id)->where('ch_group_id', $request_data['chat_id'])->where('seen', 0)->first();

            if($invite){

                $chat = \App\Models\ChGroup::where('id', $invite->ch_group_id)->first();
                if ($chat){

                    $record = $chat->chatUsers()->create([
                        'ch_group_id' => $chat->id,
                        'user_id' => $currentUser->id,
                        'is_admin' => 0
                    ]);

                    if($record){

                        $invite->seen = 1;

                        if($invite->save()){
                            return response()->json([
                                'status' => true
                            ]);
                        }
                    }

                }else{
                    return response()->json([
                        'status' => false,
                        'error' => 'Чат или группа не обнаружены'
                    ]);
                }


            }else{
                return response()->json([
                    'status' => false,
                    'error' => 'Приглашение не обнаружено'

                ]);
            }



        }else{
            return response()->json([
                'status' => false,
                'error' => 'Пользователь не авторизован'
            ]);

        }



    }

    public function refreshGroupChat(\Illuminate\Http\Request $request){

        $currentUser = Auth::user();
        $request_data = $request->all();

        if($currentUser){
            $getRecords = $this->getInvites();
            $userGroups = $this->getUserGroupChat();


            foreach($userGroups as $group){

                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'listGroups',
                    'id' => $request_data['active_chat'],
                    'group' => $group
                ]);

            }

            return response()->json([
                'status' => true,
                'chats' => $getRecords
            ]);


        }else{

            return response()->json([
                'status' => false,
                'error' => 'Пользователь не авторизован'
            ]);

        }


    }

    public function getInvites(){

        $user = Auth::user();

        $getRecords = '';

        if($user){
            $inviteData = [];
            $invites = $user->chatGroupInvites()->where('seen', 0)->get();

            if($invites->count() > 0){

                foreach($invites as $invite){
                    $chat = \App\Models\ChGroup::where('id', $invite->ch_group_id)->first();
                    $invited = \App\Models\User::where('id', $invite->sender_id)->first();


                    $inviteData['invites'][] = [
                        'channel_name' => $chat->name,
                        'invited' => [
                            'id' => $invited->id,
                            'first_name' => $invited->first_name,
                            'last_name' => $invited->last_name,
                            'image' => $invited->image ? '/storage/' . $invited->image : '/image/avatar.png',
                            'last_online_at' => $invited->last_online_at ? $invited->last_online_at->format('Y-m-d H:i:s'):null
                        ]
                    ];

                }

                $inviteData['countInvites'] = $invites->count();
                $latestInvite = $user->chatGroupInvites()->latest('created_at')->first();
                if($latestInvite){
                    $inviteData['lastInvite'] = $latestInvite->created_at->format('H:i');
                }



                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'invite',
                    'invites' => $inviteData['invites'],
                    'lastInvite' => $inviteData['lastInvite'],
                    'countInvites' => $inviteData['countInvites']
                ]);




            }

        }

        return $getRecords;



    }

    private function getUserGroupChat(){

        $groupChat = [];

        $user = Auth::user();

        if($user){

            $usersGroups = $user->groupChat()->get();

            if($usersGroups){

                $usersGroups->transform(function($userGroup) use($user){

                    $group = \App\Models\ChGroup::where('id', $userGroup->ch_group_id)->first();

                    if($group){

                        $chatUsers = $group->chatUsers()->get();
                        if($chatUsers){
                            $chatUsers->transform(function ($chatUser) {
                                $user = \App\Models\User::where('id', $chatUser->user_id)->first();

                                if($user){
                                    return [
                                        'id' => $user->id,
                                        'first_name' => $user->first_name,
                                        'last_name' => $user->last_name,
                                        'image' => $user->image,
                                        'is_admin' => $chatUser->is_admin == 1,
                                        'last_online_at' => $user->last_online_at->format('Y-m-d H:i:s')
                                    ];

                                }else{

                                    return [];
                                }



                            });

                        }

                        $lastMessage = $group->messages()->orderBy('created_at', 'DESC')->first();

                        if($lastMessage){
                            $image = $this->getImageFromAttachment($lastMessage->attachment);
                            $audio = $lastMessage->audio_file;



                            $from_id = \App\Models\User::where('id', $lastMessage->user_id)->first();
                            if($from_id){

                                $from_id = [
                                    'id' => $from_id->id,
                                    'first_name' => $from_id->first_name,
                                    'last_name' => $from_id->last_name,
                                    'image' => $from_id->image ? '/storage/' . $from_id->image : '/image/avatar.png',
                                    'last_online_at' => $from_id->last_online_at ? $from_id->last_online_at->format('Y-m-d H:i:s') : null
                                ];
                            }

                            $lastMessage = [
                                'id' => $lastMessage->id,
                                'from_id' => $from_id,
                                'body' => $lastMessage->body,
                                'audio' => $audio,
                                'image' => $image,
                                'attachment' => $lastMessage->attachment,
                                'created_at' => $lastMessage->created_at->format('Y-m-d H:i:s')
                            ];
                        }



                        return [
                            'id' => $group->id,
                            'name' => $group->name,
                            'image' => ($group->image ? '/storage/'. $group->image : ($group->chat == 1 ? '/image/groupchat.png' : '/image/channel.png')),
                            'is_admin' => $group->owner == $user->id,
                            'chat' => $group->chat == 1,
                            'users' => $chatUsers->toArray(),
                            'countUsers' => $chatUsers->count(),
                            'lastMessage' => $lastMessage,
                            'countMessages' => 0
                        ];

                    }else{
                        return [

                        ];

                    }




                });

                return $usersGroups->toArray();
            }



        }else{
            return false;
        }



    }

    public function sendMessageGroupChat(\Illuminate\Http\Request $request){

        $error = (object)[
            'status' => 0,
            'message' => null
        ];
        $attachment = null;
        $attachment_title = null;
        $reply_id = null;

        if(isset($request->reply_id)){
            $reply_id = $request->reply_id;
        }

        if($request->hasFile('file')){

            $allowed_images = Chatify::getAllowedImages();
            $allowed_files  = Chatify::getAllowedFiles();
            $allowed        = array_merge($allowed_images, $allowed_files);

            $file = $request->file('file');
            if($file->getSize() < Chatify::getMaxUploadSize()){
                if(in_array(strtolower($file->extension()), $allowed)){
                    $attachment_title = $file->getClientOriginalName();

                    $attachment = str_replace(' ', '-', $attachment_title);

                    $file->storeAs(config('chatify.attachments.folder'), $attachment, config('chatify.storage_disk_name'));
                }else{
                    $error->status = 1;
                    $error->message = 'Это расширение не поддерживается';
                }
            }else{
                $error->status = 1;
                $error->message = 'Загружаемый файл слишком большой';
            }
        }

        $messageData = '';
        if(!$error->status){

            $message = \App\Models\ChGroupMessages::create([
                'user_id' => Auth::user()->id,
                'ch_group_id' => $request->id,
                'body' => htmlentities(trim($request['message']), ENT_QUOTES, 'UTF-8'),
                'attachments' => ($attachment) ? json_encode((object)[
                    'new_name' => $attachment,
                    'old_name' => htmlspecialchars(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
                ]): null,
            ]);

            $messageData = $message;

            $view = '';

            if(!empty($messageData)){
                $dataMessage = [];
                $isFile = false;
                if(isset($messageData->attachments) && $messageData->attachments !== null){

                    $attachmentData = json_decode($messageData->attachments);
                    $dataMessage['message'] = $this->getFileType($attachmentData->new_name);
                    $isFile = true;

                }else{
                    $dataMessage['message'] = $messageData->body;
                }
                $msg = null;
                if($reply_id){
                    $msg = \App\Models\ChGroupMessages::find($reply_id);

                    if($msg){

                        $replyMessageBody = $msg->body;
                        if($msg->attachments !== null){
                            $replyAttachmentData = json_decode($msg->attachments);
                            $replyMessageBody = html_entity_decode("<img src='/storage/attachments/{$replyAttachmentData->new_name}'>");
                        }

                        $reply_from_id = \App\Models\User::find($msg->user_id);
                        if($reply_from_id){
                            $reply_from_id = [
                                'id' => $reply_from_id->id,
                                'first_name' => $reply_from_id->first_name,
                                'last_name' => $reply_from_id->last_name,
                            ];
                        }

                        $msg = [
                            'id' => $msg->id,
                            'from_id' => $reply_from_id,
                            'reply_message' => $replyMessageBody
                        ];

                    }

                }
//                TODO прикрутить реакции (пока null)
                $reaction = null;

                $dateTime = $this->formatMessageDate($messageData->created_at);

                $dataMessage['dateTitle'] = $dateTime['date'];
                $dataMessage['timeTitle'] = $dateTime['time'];
                $dataMessage['direction'] = 'sent';
                $dataMessage['msgId'] = $message->id;
                $dataMessage['delete'] = false;
                $dataMessage['id'] = $message->id;
                $dataMessage['isFile'] = $isFile;
                $dataMessage['reply'] = $msg;
                $dataMessage['date'] = $message->created_at->format('H:i');
                $dataMessage['reaction'] = $reaction;
                $dataMessage['forwarded'] = false;

                $messages[] = $dataMessage;

                $key = $this->formatDateToObject($messageData->created_at->format('Y-m-d H:i:s'));

                $view = View::make('Chatify::layouts.listItem',['get' => 'messageList', 'key'=>$key['date'], 'messages'=> $messages] )->render();

            }

            return response()->json([
                'status' => '200',
                'error' => $error,
                'message' => $view,
                'tempID' => $request['temporaryMsgId'],
            ]);


        }
    }

    public function deleteMessageChat(\Illuminate\Http\Request $request){

        $message = \App\Models\ChGroupMessages::find($request->message_id);

        if(isset($message) && !empty($message)){

            if($message->user_id == Auth::user()->id){

                if($message->delete()){

                    $messageData = [
                        'id' => $message->id,
                        'direction' => 'sent',
                        'dateTitle' => $message->created_at->format('Y-m-d'),
                        'message' => '<p>Удаленное сообщение</p>',
                        'date' => $message->created_at->format('H:i'),
                        'msgId' => $request->message_id,
                        'reply' => $message->reply_id,
                        'reaction' => null,
                        'delete' => true,
                        'forwarded' => $message->forwarded
                    ];
                    $messages[] = $messageData;

                    $view = View::make('Chatify::layouts.listItem', ['get' => 'messageList', 'messages'=> $messages])->render();

                    return response()->json([
                        'status' => true,
                        'message' => $view,
                        'message_id' => $request->message_id
                    ]);


                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Ошибка удаления сообщения'
                    ]);

                }


            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Сообщение не найдено'
                ]);
            }



        }



//        if($message){
//            $direction = null;
//            if($message->user_id == Auth::user()->id){
//                $message->hide_from = 1;
//                $direction = 'sent';
//            }else{
//                $message->hide_to = 1;
//                $direction = 'reseived';
//            }
//            $record = $message->save();
//
//            if($record){
//
//                $messageData = [
//                    'id' => $message->id,
//                    'direction' => $direction,
//                    'dateTitle' => $message->created_at->format('Y-m-d'),
//                    'message' => '<p>Удаленное сообщение</p>',
//                    'date' => $message->created_at->format('H:i'),
//                    'msgId' => $request->message_id,
//                    'reply' => $message->reply_id,
//                    'reaction' => null,
//                    'delete' => true,
//                    'forwarded' => $message->forwarded
//                ];
//                $messages[] = $messageData;
//
//                $view = View::make('Chatify::layouts.listItem', ['get' => 'messageList', 'messages'=> $messages])->render();
//
//                return response()->json([
//                    'status' => true,
//                    'message' => $view,
//                    'message_id' => $request->message_id
//                ]);
//            }else{
//                return response()->json([
//                    'status' => false,
//                    'message' => 'Ошибка удаления сообщения'
//                ]);
//
//            }
//
//
//        }else{
//            return response()->json([
//                'status' => false,
//                'message' => 'Сообщение не найдено'
//            ]);
//
//        }



    }

    public function groupChatInfo(\Illuminate\Http\Request $request){

        $currentUser = Auth::user();

        $chatData = [];


        $request_data = $request->all();

        $chat = \App\Models\ChGroup::where('id', $request_data['id'])->first();

        if($chat){
            $chatData['id'] = $chat->id;
            $chatData['name'] = $chat->name;
            $chatData['image'] = $chat->image;
            $chatData['subscribers'] = $chat->chatUsers()->get()->count() . ' участников';
            $chatData['description'] = $chat->description;
            $chatData['view'] = $chat->view ? $chat->view : 0;
            $chatData['favorite'] = $chat->favorite ? $chat->favorite : 0;
            $chatData['score'] = $chat->score ? $chat->score : 0;
            $chatData['settings'] = [];
            $chatData['userInChat'] = $chat->chatUsers()->where('user_id', Auth::user()->id)->first() ? 'Выйти' : 'Войти';

        }

        $settings = $chat->settings()->where('user_id', Auth::user()->id)->first();

        //На случай, если нет настроек пользователя. Задаем дефолтные значения (не присылать уведомления, не важный канал) (уточнить как нужно)
        //Настроки сохраняются для текущего пользователя
        if(!isset($settings) || empty($settings)) {
            $settings = $chat->settings()->create([
                'user_id' => Auth::user()->id,
                'ch_group_id' => $chat->id,
                'important' => 0,
                'notifications' => 0
            ]);

        }

        $chatData['settings'][] = ['name' => 'important', 'title' => 'Важно', 'value' => $settings->important == 1 ?? true];
        $chatData['settings'][] = ['name' => 'notifications', 'title' => 'Присылать уведомления', 'value' => $settings->notifications == 1 ?? true];

        $data = [];
        $getRecords = '';
        foreach($chatData['settings'] as $key=>$setting){
            $data['title'] = $setting['title'];
            $data['name'] = $setting['name'];
            $data['value'] = $setting['value'];
            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'chat_settings',
                'setting' => $data
            ]);

        }
        $chatData['settings'] = $getRecords;

        return response()->json(['status' => true,'chatInfo' => $chatData]);



    }

    public function chatSubscribeUnsubscribe(\Illuminate\Http\Request $request){

        $currentUser = Auth::user();

        if($currentUser){

            $chat = \App\Models\ChGroup::where('id', $request->chat_id)->first();

            if(isset($chat) && !empty($chat)){

                $userInChat = $chat->chatUsers()->where('user_id', $currentUser->id)->first();
                if(isset($userInChat) && !empty($userInChat)){

                    $record = \App\Models\ChGroupUsers::where('user_id', Auth::user()->id)->where('ch_group_id', $chat->id)->first();

                    if(isset($record) && !empty($record)){

                        if($record->delete()){

                            return response()->json([
                                'status' => true,
                            ]);


                        }else{
                            return response()->json([
                                'status' => false,
                            ]);
                        }

                    }



                }


            }


        }


    }

    public function setChatSettings(\Illuminate\Http\Request $request){

        $record = \App\Models\ChGroupSettings::where('user_id', Auth::user()->id)
            ->where('ch_group_id', $request->chat_id)
            ->first();
        if($record){
            $columnName = $request->name;
            $newValue = $request->value == 'true' ? 1 : 0;
            $record->update([$columnName => $newValue]);
        }
        $folders = $this->getFolders(Auth::user()->id, $request->folder_id);

        return response()->json([
            'status' => true,
            'userFolders' => $folders
        ]);



    }

    public function moveChatToFolder(\Illuminate\Http\Request $request){

        $request_data = $request->all();

        $currentUser = Auth::user();

        $userFolder = $currentUser->chatFolders()->where('id', $request_data['folder_id'])->first();

        if($userFolder){

            $userFolder->group_id = implode(', ', $request_data['chat_id']);

            if($userFolder->save()){
                return response()->json([
                    'status' => true,
                    'folder_id' => $userFolder->id
                ]);

            }else{
                return response()->json([
                    'status' => false,
                    'error' => 'Ошибка сохранения'
                ]);
            }

        }



    }





}
