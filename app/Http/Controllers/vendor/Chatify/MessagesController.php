<?php

namespace App\Http\Controllers\vendor\Chatify;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\ChMessage as Message;
use App\Models\ChFavorite as Favorite;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Str;
//use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class MessagesController extends Controller
{
    protected $perPage = 30;

    /**
     * Authenticate the connection for pusher
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pusherAuth(Request $request)
    {
        return Chatify::pusherAuth(
            $request->user(),
            Auth::user(),
            $request['channel_name'],
            $request['socket_id']
        );
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index( $id = null)
    {

        $user = Auth::user();

        $contacts = $this->getUserList();

        $countMessages = 0;

        $userFolders = \App\Models\ChFolders::where('user_id', $user->id)->orderBy('sort', 'asc')->get();

        if($userFolders->count() == 0){

            $defaultFolders = [
                [
                    'user_id' => $user->id,
                    'folder_name' => 'Все',
                    'sort' => 1,
                    'delete' => 0,
                    'users' => null
                ],
                [
                    'user_id' => $user->id,
                    'folder_name' => 'Личное',
                    'sort' => 2,
                    'delete' => 0,
                    'users' => null
                ]
            ];

            foreach ($defaultFolders as $folder){

                $record = \App\Models\ChFolders::create([
                    'user_id' => $folder['user_id'],
                    'folder_name' => $folder['folder_name'],
                    'sort' => $folder['sort'],
                    'delete' => $folder['delete'],
                    'users' => $folder['users']
                ]);
            }

            foreach ($contacts as $contact){
                $countMessages += $contact['countMessages'];
            }

            $folders = \App\Models\ChFolders::where('user_id', $user->id)->orderBy('sort', 'asc')->get();

            $sortedArray = collect($contacts)->sortBy(function ($item) {
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


            foreach($folders as $folder){

                if($folder->folder_name == 'Личное'){
                    $countMessages = 0;
                }


                $userFolders[$folder->id] = [
                    'id' => $folder->id,
                    'folder_name' => $folder->folder_name,
                    'sort' => $folder->sort,
                    'delete' => $folder->delete == 1,
                    'countMessages' => $countMessages,
                    'users' => $folder->folder_name == 'Все' ? $sortedArray : []
                ];

            }


        }else{

            $userFolders->transform(function ($folder) use($contacts) {
                $usersData = [];
                if ($folder->users != null && gettype($folder->users) == 'string') {

                    $userIds = explode(", ", $folder["users"]);
                    $userIds = array_map("intval", $userIds);
                    $contacts = $this->getUserList();
                    $usersData = array_filter($contacts, function ($item) use ($userIds) {
                        return in_array($item['id'], $userIds);
                    });

                }else if($folder->users == null && $folder->delete == 0 && $folder->folder_name != 'Личное'){
                    $usersData = $contacts;
                }

                $userCountMessages = 0;

                foreach ($usersData as $item) {
                    $userCountMessages += $item['countMessages'];
                }

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

                return [
                    'id' => $folder['id'],
                    'folder_name' => $folder['folder_name'],
                    'active' => $folder['folder_name'] == 'Все',
                    'sort' => $folder['sort'],
                    'countMessages' => $userCountMessages,
                    'delete' => $folder['delete'] == 1,
                    'users' => $sortedArray,

                ];

            })->sortBy('sort');
        }

        $countContacts = 0;
        foreach($contacts as $contact){
            if($contact['countMessages'] > 0){
                $countContacts++;
            }
        }




        $messenger_color = Auth::user()->messenger_color;
        return view('Chatify::pages.app', [
            'id' => $id ?? 0,
            'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
            'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
            'user' => $user,
            'contacts' => $contacts,
            'folders' => $userFolders,
            'countMessages' => $countContacts
        ]);
    }


    /**
     * Fetch data (user, favorite.. etc).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function idFetchData(Request $request)
    {
        $favorite = Chatify::inFavorite($request['id']);
        $fetch = User::where('id', $request['id'])->first();
        if($fetch){
            $userAvatar = Chatify::getUserWithAvatar($fetch)->avatar;
        }
        return Response::json([
            'favorite' => $favorite,
            'fetch' => $fetch ?? null,
            'user_avatar' => $userAvatar ?? null,
        ]);
    }

    /**
     * This method to make a links for the attachments
     * to be downloadable.
     *
     * @param string $fileName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|void
     */
    public function download($fileName)
    {
        $filePath = config('chatify.attachments.folder') . '/' . $fileName;
        if (Chatify::storage()->exists($filePath)) {
            return Chatify::storage()->download($filePath);
        }else{
            return abort(404, "Извините, файл отсутствует на сервере. Возможно он был удален!");
        }

    }

    /**
     * Send a message to database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function send(Request $request)
    {
        // default variables
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
        // if there is attachment [file]
        if ($request->hasFile('file')) {
            // allowed extensions
            $allowed_images = Chatify::getAllowedImages();
            $allowed_files  = Chatify::getAllowedFiles();
            $allowed        = array_merge($allowed_images, $allowed_files);

            $file = $request->file('file');
            // check file size
            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed)) {
                    // get attachment name
                    $attachment_title = $file->getClientOriginalName();

                    $attachment = str_replace(' ', '-', $attachment_title);

                    $file->storeAs(config('chatify.attachments.folder'), $attachment, config('chatify.storage_disk_name'));
                } else {
                    $error->status = 1;
                    $error->message = "Это расширение не поддерживается";
                }
            } else {
                $error->status = 1;
                $error->message = "Загружаемый файл слишком большой!";
            }
        }
        $messageData = '';
        if (!$error->status) {

            $message = \App\Models\ChMessage::create([
                'from_id' => Auth::user()->id,
                'to_id' => $request['id'],
                'reply_id' => ($reply_id) ? $reply_id : null,
                'body' => htmlentities(trim($request['message']), ENT_QUOTES, 'UTF-8'),
                'attachment' => ($attachment) ? json_encode((object)[
                    'new_name' => $attachment,
                    'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
                ]) : null,
            ]);

            $messageData = $message;
        }
        $view = '';
        if(!empty($messageData)){
            $dataMessage = [];
            $isFile = false;
            if (isset($messageData->attachment) && $messageData->attachment !== null) {
                $attachmentData = json_decode($messageData->attachment);
                $dataMessage['message'] = $this->getFileType($attachmentData->new_name); //html_entity_decode("<img src='/storage/attachments/{$attachmentData->new_name}'>");
                $isFile = true;
            } else {
                $dataMessage['message'] = $messageData->body;
            }
            $msg = null;
            if($reply_id){
                $msg = \App\Models\ChMessage::find($reply_id);

                if ($msg){
                    $replyMessageBody = $msg->body;
                    if ($msg->attachment !== null) {
                        $replyAttachmentData = json_decode($msg->attachment);
                        $replyMessageBody = html_entity_decode("<img src='/storage/attachments/{$replyAttachmentData->new_name}'>");
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
            }

            $reactions = $messageData->reactions()->first();
            if($reactions){
                $reaction = $reactions->reaction;
            }else{
                $reaction = null;
            }

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

            $view = View::make('Chatify::layouts.listItem',['get' => 'messageList','key'=>$key['date'], 'messages'=> $messages] )->render();

        }


        // send the response
        return response()->json([
            'status' => '200',
            'error' => $error,
            'message' => $view,
            'tempID' => $request['temporaryMsgId'],
        ]);
    }

    /**
     * fetch [user/group] messages from database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $query = Chatify::fetchMessagesQuery($request['id'])->latest();
        $messages = $query->paginate($request->per_page ?? $this->perPage);
        $totalMessages = $messages->total();
        $lastPage = $messages->lastPage();
        $response = [
            'total' => $totalMessages,
            'last_page' => $lastPage,
            'last_message_id' => collect($messages->items())->last()->id ?? null,
            'messages' => '',
        ];

        // if there is no messages yet.
        if ($totalMessages < 1) {
            $response['messages'] ='<p class="message-hint center-el"><span>Say \'hi\' and start messaging</span></p>';
            return Response::json($response);
        }
        if (count($messages->items()) < 1) {
            $response['messages'] = '';
            return Response::json($response);
        }
        $allMessages = null;
        foreach ($messages->reverse() as $message) {
            $allMessages .= Chatify::messageCard(
                Chatify::parseMessage($message)
            );
        }
        $response['messages'] = $allMessages;
        return Response::json($response);
    }

    /**
     * Make messages as seen
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function seen(Request $request)
    {
        // make as seen
        $seen = Chatify::makeSeen($request['id']);
        // send the response
        return Response::json([
            'status' => $seen,
        ], 200);
    }

    /**
     * Get contacts list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getContacts(Request $request)
    {
        // get all users that received/sent message from/to [Auth user]
        $users = Message::join('users',  function ($join) {
            $join->on('ch_messages.from_id', '=', 'users.id')
                ->orOn('ch_messages.to_id', '=', 'users.id');
        })
        ->where(function ($q) {
            $q->where('ch_messages.from_id', Auth::user()->id)
            ->orWhere('ch_messages.to_id', Auth::user()->id);
        })
        ->where('users.id','!=',Auth::user()->id)
        ->select('users.*',DB::raw('MAX(ch_messages.created_at) max_created_at'))
        ->orderBy('max_created_at', 'desc')
        ->groupBy('users.id')
        ->paginate($request->per_page ?? $this->perPage);

        $usersList = $users->items();

        if (count($usersList) > 0) {
            $contacts = '';
            foreach ($usersList as $user) {
                $contacts .= Chatify::getContactItem($user);
            }
        } else {
            $contacts = '<p class="message-hint center-el"><span>Your contact list is empty</span></p>';
        }

        return Response::json([
            'contacts' => $contacts,
            'total' => $users->total() ?? 0,
            'last_page' => $users->lastPage() ?? 1,
        ], 200);
    }

    /**
     * Update user's list item data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateContactItem(Request $request)
    {
        // Get user data
        dd($request);
        $user = User::where('id', $request['user_id'])->first();
        if(!$user){
            return Response::json([
                'message' => 'User not found!',
            ], 401);
        }
        $contactItem = Chatify::getContactItem($user);
        // send the response
        return Response::json([
            'contactItem' => $contactItem,
        ], 200);
    }

    /**
     * Put a user in the favorites list
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function favorite(Request $request)
    {
        $userId = $request['user_id'];
        // check action [star/unstar]
        $favoriteStatus = Chatify::inFavorite($userId) ? 0 : 1;
        Chatify::makeInFavorite($userId, $favoriteStatus);

        // send the response
        return Response::json([
            'status' => @$favoriteStatus,
        ], 200);
    }

    /**
     * Get favorites list
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function getFavorites(Request $request)
    {
        $favoritesList = null;
        $favorites = Favorite::where('user_id', Auth::user()->id);
        foreach ($favorites->get() as $favorite) {
            // get user data
            $user = User::where('id', $favorite->favorite_id)->first();
            $favoritesList .= view('Chatify::layouts.favorite', [
                'user' => $user,
            ]);
        }
        // send the response
        return Response::json([
            'count' => $favorites->count(),
            'favorites' => $favorites->count() > 0
                ? $favoritesList
                : 0,
        ], 200);
    }

    /**
     * Search in messenger
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function search(Request $request)
    {
        $getRecords = null;
        $input = trim(filter_var($request['input']));
        $records = \App\Models\ChMessage::where('to_id', Auth::id())->where('from_id', $request->userId)->where('body', 'LIKE', "%{$input}%")->where('delete_to', 0)
            ->orWhere('to_id', $request->userId)->where('from_id', Auth::id())->where('delete_from', 0)->where('body', 'LIKE', "%{$input}%")->paginate($request->per_page ?? $this->perPage);

        $records->transform(function ($record) {

            $to_id = \App\Models\User::where('id', $record->to_id)->first();
            if($to_id){
                $to_id = [
                    'id' => $to_id->id,
                    'first_name' => $to_id->first_name,
                    'last_name' => $to_id->last_name,
                    'image' => $to_id->image ? '/storage/' . $to_id->image : '/image/avatar.png',
                    'last_online_at' => $to_id->last_online_at ? $to_id->last_online_at->format('Y-m-d H:i:s') : null
                ];
            }

            $from_id = \App\Models\User::where('id',$record->from_id)->first();
            if($from_id){
                $from_id = [
                    'id' => $from_id->id,
                    'first_name' => $from_id->first_name,
                    'last_name' => $from_id->last_name,
                    'image' => $from_id->image ? '/storage/' . $from_id->image : '/image/avatar.png',
                    'last_online_at' => $from_id->last_online_at ? $from_id->last_online_at->format('Y-m-d H:i:s') : null,
                ];
            }

            return [
                'id' => $record->id,
                'body' => $record->body,
                'attachment' => $record->attachment,
                'audio_file' => $record->audio_file,
                'to_id' => $to_id,
                'from_id' => $from_id,
                'seen' => $record->seen,


            ];


        });

        if($records->total() < 1){
            $getRecords = '<p style="align-self: center;"><span>Ничего не найдено</span></p>';
        }else{
            foreach ($records->items() as $record){
                $getRecords .= view('Chatify::layouts.listItem', [
                    'get' => 'message',
                    'message' => $record

                ]);

            }
        }

        return Response::json([
            'records' => $getRecords,
            'total' => $records->total(),
            'last_page' => $records->lastPage()
        ], 200);
    }

    /**
     * Get shared photos
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function sharedPhotos(Request $request)
    {
        $shared = Chatify::getSharedPhotos($request['user_id']);
        $sharedPhotos = null;

        // shared with its template
        for ($i = 0; $i < count($shared); $i++) {
            $sharedPhotos .= view('Chatify::layouts.listItem', [
                'get' => 'sharedPhoto',
                'image' => Chatify::getAttachmentUrl($shared[$i]),
            ])->render();
        }
        // send the response
        return Response::json([
            'shared' => count($shared) > 0 ? $sharedPhotos : '<p class="message-hint"><span>Nothing shared yet</span></p>',
        ], 200);
    }

    /**
     * Delete conversation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteConversation(Request $request)
    {
        // delete
        $delete = Chatify::deleteConversation($request['id']);

        // send the response
        return Response::json([
            'deleted' => $delete ? 1 : 0,
        ], 200);
    }

    /**
     * Delete message
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMessage(Request $request)
    {
        // delete
        $delete = Chatify::deleteMessage($request['id']);

        // send the response
        return Response::json([
            'deleted' => $delete ? 1 : 0,
        ], 200);
    }

    public function updateSettings(Request $request)
    {
        $msg = null;
        $error = $success = 0;

        // dark mode
        if ($request['dark_mode']) {
            $request['dark_mode'] == "dark"
                ? User::where('id', Auth::user()->id)->update(['dark_mode' => 1])  // Make Dark
                : User::where('id', Auth::user()->id)->update(['dark_mode' => 0]); // Make Light
        }

        // If messenger color selected
        if ($request['messengerColor']) {
            $messenger_color = trim(filter_var($request['messengerColor']));
            User::where('id', Auth::user()->id)
                ->update(['messenger_color' => $messenger_color]);
        }
        // if there is a [file]
        if ($request->hasFile('avatar')) {
            // allowed extensions
            $allowed_images = Chatify::getAllowedImages();

            $file = $request->file('avatar');
            // check file size
            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed_images)) {
                    // delete the older one
                    if (Auth::user()->avatar != config('chatify.user_avatar.default')) {
                        $avatar = Auth::user()->avatar;
                        if (Chatify::storage()->exists($avatar)) {
                            Chatify::storage()->delete($avatar);
                        }
                    }
                    // upload
                    $avatar = Str::uuid() . "." . $file->extension();
                    $update = User::where('id', Auth::user()->id)->update(['avatar' => $avatar]);
                    $file->storeAs(config('chatify.user_avatar.folder'), $avatar, config('chatify.storage_disk_name'));
                    $success = $update ? 1 : 0;
                } else {
                    $msg = "File extension not allowed!";
                    $error = 1;
                }
            } else {
                $msg = "File size you are trying to upload is too large!";
                $error = 1;
            }
        }

        // send the response
        return Response::json([
            'status' => $success ? 1 : 0,
            'error' => $error ? 1 : 0,
            'message' => $error ? $msg : 0,
        ], 200);
    }

    /**
     * Set user's active status
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setActiveStatus(Request $request)
    {
        $activeStatus = $request['status'] > 0 ? 1 : 0;
        $status = User::where('id', Auth::user()->id)->update(['active_status' => $activeStatus]);
        return Response::json([
            'status' => $status,
        ], 200);
    }

    private function formatMessageDate($dateString) {
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

    private function getUserList(){

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

    private function getFileType($file)
    {

        $filename = $file;

        $result = '';
        $parts = explode('.', $filename);
        $originalName = $parts[0];


        if ($filename) {
            $fileDirectory = '/storage/attachments/';
            $filePath = $fileDirectory . $filename;
            $publicPath = public_path($filePath);

            if (file_exists($publicPath)) {
                $extension = pathinfo($filename, PATHINFO_EXTENSION);

//                dd($extension);

                $playableExtensions = ["mp4", "mov", "avi", "mpegps", "wmv"];
                $audio = ["wav", "mp3"];
                $images = ["png", "jpg", "jpeg", "gif"];
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
                    $result = html_entity_decode("<img src='/storage/attachments/{$filename}'>");
                } else {
                    $result = '<a href="'.$filePath.'">Скачать '.$originalName.'</a>';

                }

            } else {
                $result = "Файл не найден.";
            }
            return $result;

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
            }else{
                $result = null;
            }
        }
        return $result;
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

}
