@include('partial.headerSubView')
<!-- @@include is used to include the sub view file in the main view file -->
<h1>Main View</h1>
<p>This is the main view</p>
<!-- @@include is used to include the sub view file in the main view file and pass the data to the sub view file -->
@include('partial.bodysubview', ['bodydata' => 'This is the body data passing from the main view to the body sub view'])
<!-- @@includeIf is used to include the sub view file in the main view file if the condition is true -->
@includeIf('partial.footerSubView', ['footerdata' => 'This is the footer data passing from the main view to the footer sub view'])