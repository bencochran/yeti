<tr class="<?php echo ($i%2) ? 'odd' : 'even';?> <?php echo ($message->read) ? 'read' : 'unread'; ?>">
	<td class="marker"><span><?php echo ($message->read)? '&nbsp;' : '&bull;' ?></span></td>
	<td class="from_user">
		<?php $this->renderElement('user_byline', array('user' => $message->from_user)); ?>
	</td>
	<td class="time"><?php $this->renderElement('datetime', array('time' => $message->ctime)) ?></td>
	<td class="subject"><a title="Read message" href="<?php echo WWW_BASE_PATH ?>message/<?php echo $message->mid ?>"><?php echo $message->subject; ?></a></td>
</tr>