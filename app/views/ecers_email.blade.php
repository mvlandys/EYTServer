
<h3>App Notes</h3>
<ul>
@foreach($app_notes as $note)
<li>
    Note: {{$note["note"]}}<br/>
</li>
@endforeach
</ul>
<br/><br/>

<h3>Page Notes</h3>
<ul>
@foreach($notes as $note)
<li>
    Test: {{$note["test"]}}<br/>
    Page: {{$note["page"]}}<br/>
    Note: {{$note["note"]}}<br/>
</li>
@endforeach
</ul>
<br/><br/>

<h3>Question Answers</h3>
<ul>
    @foreach($questions as $question)
        <li>
            Item: {{$question["item"]}}<br/>
            Answer: {{$question["answer"]}}<br/>
        </li>
    @endforeach
</ul>