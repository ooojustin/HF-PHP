<?php

    class HF_API {
        
        const API_URL = "https://hackforums.net/api/v1/";
        
        private $api_key;
        private $user_agent;
        
        function __construct($api_key, $user_agent) {
            $this->api_key = $api_key;
            $this->user_agent = $user_agent;
        }
        
        // Returns the current API version from the server.
        function get_version() {
            $url = self::API_URL . '?version';
            $raw = file_get_contents($url, false, $this->get_context());
            $response = json_decode($raw, true);
            return $response['apiVersion'];
        }
        
        // Returns information about a user, given the UID.
        function get_user_information($uid) {
            $path = 'user/' . $uid;
            return $this->api_request($path);
        }
        
        // Returns information about a category, given the CID.
        function get_category_information($cid) {
            $path = 'category/' . $cid;
            return $this->api_request($path);
        }
        
        // Returns information about a forum, given the FID.
        // Includes all threads inside the forum, to be used for navigation.
        function get_forum_information($fid) {
            $path = 'forum/' . $fid;
            return $this->api_request($path);
        }
        
        // Returns information about a thread, given the TID.
        // Includes 10 posts on the specified page number. Default page = 1.
        function get_thread_information($tid, $page = 1, $raw = true) {
            $path = 'thread/' . $tid . '?page=' . $page;
            if ($raw) $path = str_replace('?page=', '?raw&page=', $path);
            return $this->api_request($path);
        }
        
        // Returns information about a post, given the PID;
        function get_post_information($pid, $raw = true) {
            $path = 'post/' . $pid . ($raw ? '?raw' : '');
            return $this->api_request($path);
        }
        
        // Returns a struct containing information about the specified InboxType alongside a list of messages.
        function get_private_message_container($box = InboxType::Inbox, $page = 1) {
            $path = 'pmbox/' . $box . '?page=' . $page;
            return $this->api_request($path);
        }
        
        // Returns a list of private messages from the specified inbox type.
        // Note that it will return up to 25 messages, depending on the specified page.
        function get_private_messages($box = InboxType::Inbox, $page = 1) {
            $container = $this->get_private_message_container($box, $page);
            return $container['pms'];
        }
        
        // Returns information about a private message, including the actual 'message' content from a PMID.
        function get_private_message($pmid) {
            $path = 'pm/' . $pmid;
            return $this->api_request($path);
        }
        
        // Returns information about a HackForums group given the GID.
        function get_group_information($gid) {
            $path = 'group/' . $gid;
            return $this->api_request($path);
        }
        
        // Sends an API request, parses the result. Throws an exception if the request fails.
        // If the query is completed successfully, it returns the 'result' property, typically as an associate array.
        private function api_request($path) {
            $url = self::API_URL . $path;
            $raw = file_get_contents($url, false, $this->get_context());
            $response = json_decode($raw, true);
            if ($response['success'])
                return $response['result'];
            else
                throw new Exception('HF-API Request Failed: ' . $response['message']);
        }
        
        // Creates a stream context with user agent/authorization to be used for API requests.
        private function get_context() {
            $options = array(
                'http' => array(
                    'method' => 'GET',
                    'header' => 
                        'user-agent: ' . $this->user_agent . "\r\n" .
                        'authorization: Basic ' . base64_encode($this->api_key . ':') . "\r\n"
                )
            );
            return stream_context_create($options);
        }
        
    }

    // Known inbox type ids, used to retrieve PMs from different sections of a users private messages.
    class InboxType {
        const Inbox = 1;
        const Sent = 2;
        const Drafts = 3;
        const Trash = 4;
    }

    // Status of the message in the users inbox.
    class MessageStatus {
        const Unopened = 0; // the message is in the users inbox and has not yet been opened
        const Opened = 1; // the message has been opened by the user, but not replied to yet
        const Unknown = 2; // ??? (i couldn't find any pms with this status)
        const RepliedTo = 3; // we've replied to the message
    }

?>