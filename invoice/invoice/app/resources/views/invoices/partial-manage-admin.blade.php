<div class="form-group'">
    <label>Select Manager</label>
    <select class="form-control manager-list">
        <option value="">Select Manager</option>
        @foreach($managers as $manager)
        <option value="{{$manager->id}}">{{$manager->name}}</option>
        @endforeach
    </select>
</div>