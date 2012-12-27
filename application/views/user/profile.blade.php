@layout('layouts.main')
@section('content')
<? //dd($User->profile);
$tab = Bootstrapper\Tabbable::tabs_left(
  array(
    //array('label'=>Navigation::HEADER, 'url' => 'Sections'),
    array('label'=>'Profile', 'url' => '#', 'active' => true, 'content' => "<p>I'm in Section 1.</p>"),
    array('label'=>'Photos', 'url'=>'#', 'content' => "<p>I'm in Section 1.</p>"),
    array('label'=>'Friends', 'url'=>'#', 'content' => "<p>I'm in Section 1.</p>"),
    array('label'=>'Interests', 'url'=>'#', 'content' => "<p>I'm in Section 1.</p>"),
  )//, true
);
?>

<div class="tabbable tabs-left">
  <ul class="nav nav-tabs nav-pills">{{-- nav-pills l'ho aggiunto per colorare il tab --}}
    <li class="active"><a href="#tab_Gia3l_0" data-toggle="tab">{{__('interface.profile')}}</a></li>
    <li><a href="#tab_Gia3l_1" data-toggle="tab">{{__('interface.photos')}}</a></li>
    <li><a href="#tab_Gia3l_2" data-toggle="tab">{{__('interface.friends')}}</a></li>
    <li><a href="#tab_Gia3l_3" data-toggle="tab">{{__('interface.interests')}}</a></li>
  </ul>
  <div class=" tab-content">
    <div class="tab-pane active" id="tab_Gia3l_0">
      <div class="span6">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th>{{__('user.first_name')}}</th>
              <td>{{$User->profile->first_name}}</td>
            </tr>
            <tr>
              <th>{{__('user.last_name')}}</th>
              <td>{{$User->profile->last_name}}</td>
            </tr>
            <tr>
              <th>Email</td>
              <td>{{$User->email}}</th>
            </tr>
            <tr>
              <th>{{__('user.birth_date')}}</th>
              <td>{{$User->profile->birth_date}}</td>
            </tr>
          </tbody>
        </table>
        </div>
    </div>
    <div class="tab-pane" id="tab_Gia3l_1">
      <p>Howdy, I'm in Section 2.</p>
    </div>
    <div class="tab-pane" id="tab_Gia3l_2">
      <div class="span6">
        <table class="table table-bordered">
            <thead>
              <tr>
                <th>{{__('user.name')}}</th>
                <th>{{__('user.birth_date')}}</th>
              </tr>
            </thead>
          <tbody>
            @foreach($Friends as $friend)
            <tr>
              <td><a href="/profile/{{$friend->id}}">{{$friend->name}}</a></td>
              <td><a id="username" data-type="text" data-pk="1" data-url="/profile/livedit" data-original-title="Enter username" href="#">{{$friend->name}}</a></td>
              <td>{{$friend->profile->birth_date}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        </div>
    </div>
    <div class="tab-pane" id="tab_Gia3l_3">
      <p>What up girl, this is Section 3.</p>
    </div>
  </div>
</div>


@endsection
@section('dynamicscripts')
{{ Asset::container('bootstrapper-datepicker')->scripts() }}
{{ Asset::container('bootstrapper-datepicker')->styles() }}
{{ Asset::container('bootstrapper-x-editable')->scripts() }}
{{ Asset::container('bootstrapper-x-editable')->styles() }}
{{Bootstrapper\Javascripter::add_js_snippet('$("#username").editable();')}}
{{Bootstrapper\Javascripter::write_javascript()}}
@endsection
