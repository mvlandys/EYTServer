

@extends('layout')

@section('content')

<h2>Questionnaire</h2>

<br/>

<form action="/questionnaire/form/submit" method="post">
    <div class="well">
        <div class="row">
            <div class="col-sm-3">
                <input type="text" name="test_name" class="form-control" placeholder="Test Name..." />
            </div>
            <div class="col-sm-3">
                <input type="text" name="subject_id" class="form-control" placeholder="Subject ID..." value="{{{ $subject_id }}}" />
            </div>
            <div class="col-sm-3">
                <input type="text" name="session_id" class="form-control" placeholder="Session ID..." />
            </div>
            <div class="col-sm-3">
                <input type="text" name="grade" class="form-control" placeholder="Grade..." />
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-sm-3">
                <input type="text" name="dob" class="form-control" placeholder="dd/mm/yyyy" />
            </div>
            <div class="col-sm-3">
                <input type="text" name="age" class="form-control" placeholder="Age..." />
            </div>
            <div class="col-sm-3">
                <select name="sex" class="form-control">
                    <option value="0">Gender</option>
                    <option value="1" {{{ ($sex == 1) ? "selected" : "" }}} >Male</option>
                    <option value="2" {{{ ($sex == 2) ? "selected" : "" }}} >Female</option>
                </select>
            </div>
            <div class="col-sm-3">
                <select id="responseType" class="form-control">
                    <option value="0">Response Type</option>
                    <option value="1" {{{ ($type == 1) ? "selected" : "" }}}>3 Answers</option>
                    <option value="2" {{{ ($type == 2) ? "selected" : "" }}}>5 Answers</option>
                </select>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>1. Considerate of other people's feelings</label>
        </div>
        <div class="col-sm-6">
            <select name="1" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>2. Restless, overactive, cannot stay still for long</label>
        </div>
        <div class="col-sm-6">
            <select name="2" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>3. Often complains of headaches, stomach-aches or sickness</label>
        </div>
        <div class="col-sm-6">
            <select name="3" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>4. Shares readily with other children (treats, toys, pencils etc.) </label>
        </div>
        <div class="col-sm-6">
            <select name="4" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>5. Often has temper tantrums or hot tempers </label>
        </div>
        <div class="col-sm-6">
            <select name="5" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>6. Rather solitary, tends to play alone </label>
        </div>
        <div class="col-sm-6">
            <select name="6" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>7. Generally obedient, usually does what adults request </label>
        </div>
        <div class="col-sm-6">
            <select name="7" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>8. Many worries, often seems worried </label>
        </div>
        <div class="col-sm-6">
            <select name="8" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>9. Helpful if someone is hurt, upset or feeling ill </label>
        </div>
        <div class="col-sm-6">
            <select name="9" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>10.Constantly fidgeting or squirming </label>
        </div>
        <div class="col-sm-6">
            <select name="10" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>11. Has at least one good friend </label>
        </div>
        <div class="col-sm-6">
            <select name="11" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>12. Often fights with other children or bullies them </label>
        </div>
        <div class="col-sm-6">
            <select name="12" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>13. Often unhappy, down-hearted or tearful </label>
        </div>
        <div class="col-sm-6">
            <select name="13" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>14. Generally liked by other children </label>
        </div>
        <div class="col-sm-6">
            <select name="14" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>15. Easily distracted, concentration wanders </label>
        </div>
        <div class="col-sm-6">
            <select name="15" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>16.Nervous or clingy in new situations, easily loses confidence </label>
        </div>
        <div class="col-sm-6">
            <select name="16" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>17 Kind to younger children </label>
        </div>
        <div class="col-sm-6">
            <select name="17" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>18. Often argumentative with adults </label>
        </div>
        <div class="col-sm-6">
            <select name="18" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>19. Picked on or bullied by other children </label>
        </div>
        <div class="col-sm-6">
            <select name="19" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>20. Often volunteers to help others (parents, teachers, other children) </label>
        </div>
        <div class="col-sm-6">
            <select name="20" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>21. Can stop and think things out before acting </label>
        </div>
        <div class="col-sm-6">
            <select name="21" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>22.Can be spiteful to others </label>
        </div>
        <div class="col-sm-6">
            <select name="22" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>23. Gets on better with adults than with other children </label>
        </div>
        <div class="col-sm-6">
            <select name="23" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>24.Many fears, easily scared </label>
        </div>
        <div class="col-sm-6">
            <select name="24" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>25. Sees tasks through to the end, good attention span</label>
        </div>
        <div class="col-sm-6">
            <select name="25" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>26. Is calm and easy-going</label>
        </div>
        <div class="col-sm-6">
            <select name="26" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>27. likes to work things out for self; seeks help as last resort</label>
        </div>
        <div class="col-sm-6">
            <select name="27" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>28. Shows wide mood swings</label>
        </div>
        <div class="col-sm-6">
            <select name="28" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>29. Can work easily with others</label>
        </div>
        <div class="col-sm-6">
            <select name="29" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>30. Does not need much help with tasks</label>
        </div>
        <div class="col-sm-6">
            <select name="30" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>31. Gets over-excited</label>
        </div>
        <div class="col-sm-6">
            <select name="31" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>32. Says “please” and “thank you” when reminded</label>
        </div>
        <div class="col-sm-6">
            <select name="32" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>33. chooses activities for themselves</label>
        </div>
        <div class="col-sm-6">
            <select name="33" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>34. Is easily frustrated</label>
        </div>
        <div class="col-sm-6">
            <select name="34" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>35. gets over being upset quickly</label>
        </div>
        <div class="col-sm-6">
            <select name="35" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>36. Persists in the face of difficulties</label>
        </div>
        <div class="col-sm-6">
            <select name="36" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>37. Waits his/her turn in games</label>
        </div>
        <div class="col-sm-6">
            <select name="37" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>38. Cooperates with requests</label>
        </div>
        <div class="col-sm-6">
            <select name="38" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>39. Can move easily to a new activity after finishing a task</label>
        </div>
        <div class="col-sm-6">
            <select name="39" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <label>40. Is impulsive, acts without thinking</label>
        </div>
        <div class="col-sm-6">
            <select name="40" class="form-control">
                <option value=".">Please Select</option>
                <option value="1">Not True</option>
                <option value="3">Somewhat True</option>
                <option value="5">Certainly True</option>
            </select>
        </div>
    </div>
    <br/><br/>
    <div class="text-center">
        <button type="submit" class="btn btn-success btn-lg btn-block">Submit Answers</button>
    </div>
</form>

<br/><br/>

@stop