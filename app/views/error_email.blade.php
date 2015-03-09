<b>URL:</b> {{ $url }}<br/><br/>
<b>Type:</b> {{ $type }}<br/><br/>
<b>Error Code:</b> {{ $code }}<br/><br/>
<p><b>Error: {{ $error }}</b></p>
<p>On line {{ $line }} from file: {{ $file }}</p>

<pre><?php var_dump(Input::all()); ?></pre>