@include('partial.headerSubView')
<!-- @@include is used to include the sub view file in the main view file -->
<h1>Main View</h1>
<p>This is the main view</p>
@include('partial.bodysubview', ['bodydata' => 'This is the body data passing from the main view to the body sub view'])