<div style="margin: 15px">
    <p>Hi {{ $user->username }},</p>
    <p>Please click on the following link to reset your password:</p>
    <p><a href="http://{{ Config::get('app.url') }}/passwordreset/{{ $code }}/"</p>
    <p>Regards,<br/>EYT Server</p>
</div>