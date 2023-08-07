<div style="font-family: Helvetica,Arial,sans-serif;min-width:500px;overflow:auto;line-height:2">
    <div style="margin:10px auto;width:70%;padding:10px 0">
      <div style="border-bottom:1px solid #eee">
        <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Employee Rating</a>
      </div>
      <p style="font-size:1.1em">Hi, <b>{{ $mailData['name'] }}</b></p>
      <h3>Forgot Password?</h3>
      <p>{{ $mailData['title'] }}</p>
      <center><h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">
        {{$mailData['otp']}}</h2></center>
        <br>
        <p style="font-size:0.9em;">Regards,<br />Employee Rating</p>
        <hr style="border:none;border-top:1px solid #eee" />
        <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
        </div>
      </div>
    </div>
  </div>