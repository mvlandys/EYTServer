<b>URL:</b> {{ $url }}<br/><br/>
<p><b>Error: {{ $error }}</b></p>
<p>On line {{ $line }} from file: {{ $file }}</p>

<pre><?php var_dump(Input::all()); ?></pre>