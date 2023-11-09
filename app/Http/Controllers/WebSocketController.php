<?php

namespace App\Http\Controllers;

//use \Illuminate\Http\Request;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Models\User;
use App\Models\UserSubscribe;


class WebSocketController extends Controller implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryArray);
        $usersSubscribe = [];
        if(isset($queryArray['user_id'])){

            $users = UserSubscribe::where('user_subscribe_id', $queryArray['user_id'])->get();
            if($users){
                foreach($users as $item){
                    $query = User::find($item->user_id);
                    if($query->exists()){
                        foreach ($query->get() as $user){
                            $usersSubscribe[] = [
                                'id' => $user->id,
                                'first_name' => $user->first_name,
                                'last_name' => $user->last_name,
                                'image' => $user->image
                            ];
                        }
                    }
                }
            }
            $conn->send(json_encode($usersSubscribe));
        }

    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg);
        $userList = [];
        if(isset($data->type)){
            if($data->type == 'user_list'){
                $usersSubscribe = UserSubscribe::where('user_subscribe_id', $data->user_id)->orderBy('id');

                if($usersSubscribe->exists()){
                    foreach ($usersSubscribe->get()->toArray() as $item){
                        $query = User::find($item['user_id']);
                        if($query->exists()){
                            foreach($query->get() as $user){
                                $userList[] = [
                                    'id' => $user->id,
                                    'first_name' => $user->first_name,
                                    'last_name' => $user->last_name,
                                    'image' => $user->image
                                ];
                            }
                        }
                    }
                }
                $send_data['users'] = $userList;
                $sender_connection_id = User::select('connection_id')->where('id', $data->user_id)->get();
                foreach ($this->clients as $client){
                    if($client->resourceId == $sender_connection_id[0]->connection_id){
                        $client->send(json_encode($send_data));
                    }
                }

            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Ошибка: {$e->getMessage()}";

        $conn->close();
    }
}
