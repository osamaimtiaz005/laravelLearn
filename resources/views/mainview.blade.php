@include('partial.headerSubView')
<!-- @@include is used to include the sub view file in the main view file -->
<h1>Main View</h1>
<p>This is the main view</p>
<!-- @@include is used to include the sub view file in the main view file and pass the data to the sub view file -->
@include('partial.bodysubview', ['bodydata' => 'This is the body data passing from the main view to the body sub view'])
<!-- @@includeIf is used to include the sub view file in the main view file if the condition is true -->
@includeIf('partial.footerSubView', ['footerdata' => 'This is the footer data passing from the main view to the footer sub view'])


<x-messagebanner message="This is a warning message" class="warning" />
<x-messagebanner message="This is a success message" class="success" />
<x-messagebanner message="This is a error message" class="error" />


<style>
    .warning {
        color: orange;
        background-color: yellow;
        padding: 10px;
        margin: 30px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        font-family: Arial, sans-serif;
        display: inline-block;
    }

    .success {
        color: green;
        background-color: lightgreen;
        padding: 10px;
        margin: 30px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        font-family: Arial, sans-serif;
        display: inline-block;
    }

    .error {
        color: red;
        background-color: lightcoral;
        padding: 10px;
        margin: 30px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        font-family: Arial, sans-serif;
        display: inline-block;
    }
</style>