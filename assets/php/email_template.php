<?php
$host = $_SERVER['HTTP_HOST'];
?>
<STYLE type="text/css">
	.ReadMsgBody
	{ width: 100%; height: 100%;}
	.ExternalClass
	{width: 100%; height: 100%;}
	html, body {
		margin: 0;
		padding: 0;
	}
</STYLE>
<table style="background: #3e3f3f; color: #444; width: 100%; height: 100%; font-family: arial, sans-serif;" border="0" cellspacing="0">
	<tbody>
		<tr>
			<td>
				<table style="width: 600px; height: 100%; background: #fff;" border="0" cellspacing="0" cellpadding="10" align="center">
					<tbody>
						<tr>
							<td style="vertical-align: top;">
								<?php echo $body; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>