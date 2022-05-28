<?php
   	$chat_item = '';
	$chat_item_sel = '';
	$chat_all_user = '';
	$chat_all_item_list = '';
	$chat_count = 0;
		
	$chat_all_stores = $this->m_stores->query_user_stores($log_user_id);
	if(!empty($chat_all_stores)) {
		foreach($chat_all_stores as $chat_stores){
			$chat_allstore = $this->m_stores->query_user_stores_id($chat_stores->store_id);
			if(!empty($chat_allstore)){
				foreach($chat_allstore as $chat_store){
					//get all users
					if($log_user_id != $chat_store->user_id){
						//get users
						$chat_count = 0;
						$chat_guser = $this->users->query_single_user_id($chat_store->user_id);
						if(!empty($chat_guser)){
							foreach($chat_guser as $guser){
								$c_user_firstname = $guser->firstname;
								$c_user_lastname = $guser->lastname;
								$c_user_pics_small = $guser->pics_small;
								$c_user_status = $guser->user_status;
								
								if($c_user_status == 1){$c_status = 'online';}else{$c_status = 'away';}
								
								if($c_user_pics_small=='' || file_exists(FCPATH.$c_user_pics_small)==FALSE){$c_user_pics_small='img/icon57.png';}
								
								//get user chat details
								$chat_all_item_list .= '
									<div id="chat-show'.$chat_count.'" class="chat-talk display-none">
										<div class="chat-talk-info sidebar-section">
											<button id="chat-talk-close-btn" class="btn btn-xs btn-default pull-right">
												<i class="fa fa-times"></i>
											</button>
											<img src="'.base_url($c_user_pics_small).'" alt="avatar" class="img-circle pull-left">
											<strong>'.$c_user_firstname.'</strong> '.$c_user_lastname.'
										</div>
		
										<ul class="chat-talk-messages">
											<li class="text-center"><small>Yesterday, 18:35</small></li>
											<li class="chat-talk-msg animation-slideRight">Hey admin?</li>
											<li class="chat-talk-msg animation-slideRight">How are you?</li>
											<li class="text-center"><small>Today, 7:10</small></li>
											<li class="chat-talk-msg chat-talk-msg-highlight themed-border animation-slideLeft">Am fine, thanks!</li>
										</ul>
		
										<form action="" method="post" id="sidebar-chat-form'.$chat_count.'" class="chat-form">
											<input type="text" id="sidebar-chat-message" name="sidebar-chat-message" class="form-control form-control-borderless" placeholder="Type a message..">
										</form>
									</div>
								';
								
								$chat_all_user .= '
									<li>
										<a href="javascript:void(0)" class="chat-user-'.$c_status.' enable-tooltip" data-placement="bottom" title="'.$c_user_firstname.' '.$c_user_lastname.'">
											<span></span>
											<img src="'.base_url($c_user_pics_small).'" alt="avatar" class="img-circle">
										</a>
									</li>
								';
							}
						}
					}
					
				}
			}
		}
	}
?>