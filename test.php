<html>
<?php

    include 'hf-php.php';

    echo 'Initializing API...<br>';
    $api = new HF_API('', 'HF-PHP');
    echo 'Completed. Version #' . $api->get_version();

    echo '<br><br>Testing get_user_information...<br>';
    $user = $api->get_user_information(3241222);
    echo 'username: ' . $user['username'];
    
    echo '<br><br>Testing get_category_information...<br>';
    $category = $api->get_category_information(151);
    echo 'category name: ' . $category['name'];
    
    echo '<br><br>Testing get_forum_information...<br>';
    $forum = $api->get_forum_information(208);
    echo 'forum name: ' . $forum['name'];
    
    echo '<br><br>Testing get_thread_information...<br>';
    $thread = $api->get_thread_information(5665556);
    echo 'thread name: ' . $thread['subject'];
    
    echo '<br><br>Testing get_post_information...<br>';
    $post = $api->get_post_information(58564495);
    echo 'post message: ' . $post['message'];
    
    echo '<br><br>Testing get_private_message_container...<br>';
    $privateMessageContainer = $api->get_private_message_container();
    echo 'box information: ' . $privateMessageContainer['pmbox'] . ', ' . $privateMessageContainer['pageInfo']['total'] . ' messages';
    
    echo '<br><br>Testing get_private_messages...<br>';
    $messages = $api->get_private_messages();
    echo 'got messages: ' . count($messages) . ' total, first id: ' . $messages[0]['pmid'];
    
    echo '<br><br>Testing get_private_message...<br>';
    $message = $api->get_private_message($messages[0]['pmid']);
    echo 'got message: from ' . $message['fromusername'] . ', to ' . $message['tousername'] . ', subject = ' . $message['subject'];
    
    echo '<br><br>Testing get_group_information...<br>';
    $group = $api->get_group_information(52);
    echo 'group: ' . $group['name'] . ', owner: ' . $group['owner']['username'];
    
?>
</html>