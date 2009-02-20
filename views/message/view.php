<table class="message">
	<tr class="from">
		<td class="label">From:</td>
		<td><?php $this->renderElement('user_byline', array('user' => $message->from_user)); ?></td>
	</tr>
	
	<tr class="to">
		<td class="label">To:</td>
		<td><?php $this->renderElement('user_byline', array('user' => $message->to_user)); ?></td>
	</tr>
	
	<tr class="time">
		<td class="label">Sent:</td>
		<td><?php $this->renderElement('datetime', array('time' => $message->ctime)) ?></td>
	</tr>
	
	<tr class="subject">
		<td class="label">Subject:</td>
		<td><?php echo $message->subject; ?></td>
	</tr>
	
	<tr class="body">
		<td class="label">Body:</td>
		<td><?php echo $message->body; ?></td>
	</tr>
</table>