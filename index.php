<!DOCTYPE html>
<html>

<head>
    <title>WebRTC Test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

<div class="row" style="margin:10px;">
    <div class="col-md-3">
        <div id="temp-selector" style=" box-shadow: 0px 0px 5px grey; margin-right: 10px; width:100%; height:100%; min-height:1000px; padding:10px;">
            
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="aInput">Select File Input</label>
            <input type="file" name="file" class="form-control-file" id="aInput">
        </div>
        <button type="button" class="btn btn-primary" onclick="addDiv()">Add Pane</button>

        <div id="container" style="width: 100%; height: 500px; padding:10px;">
            <div id="container_holder" style="position:relative; width:100%; height:100%; box-shadow: 0px 0px 5px grey; background-color:#e5e5e5; display: inline-block;"></div>     
        </div>
        <button type="button" class="btn btn-success" onclick="downloadImage()">Download Image</button>
    </div>
    <div class="col-md-3">
        <div  style=" box-shadow: 0px 0px 5px grey; margin-right: 10px; width:100%; height:100%; min-height:1000px; padding:10px;">
            <form id="text-area-pane">
                <button type="submit" name="submit" class="btn btn-default" >Save Template</button>
            </form>
        </div>    
    </div>
</div>

<script type="text/javascript">
console.log("CORE READY");

var countDiv = 0;

function addDiv() {
    var date = Date.now();
    console.log("Add Div Called");
    $("#container_holder").append('<div style="" id="text-pane-' + date + '">hello</div>');
    $("#text-pane-" + date).css("position", "absolute");
    $("#text-pane-" + date).css("top", 50 * countDiv + 10);
    addCssTextArea("text-pane-" + date);
    countDiv++;
}

function addCssTextArea(id) {
    console.log("Adding listener");
    // onchange="textChanged(' + id +', ' + id + '-area)"
    $("#text-area-pane").append('<div class="form-group">' +
        '<label for="comment">' + id + ':</label>'
        + '<textarea class="form-control" rows="3" name="' + id + '" id="' + id + '-area" placeholder="' + id + '-area"></textarea>'
        + "<br/>"
        +'<button type="button" class="btn btn-danger right" id="' + id + '-btn">X</button>'
        + '</div>');

    $('#' + id + '-btn').on("click", function() {
        $("#" + id).remove();
        $("#" + id + "-area").parent().remove();
        countDiv = 0;
    });

    $("#" + id + '-area').on('change textInput input', function() {
        // console.log("changed");
        // console.log($(this).val());
        var val1 = $(this).val();
        if (val1.indexOf("\n") > -1) {
            val1 = val1.split("\n");
            console.log(val1)
            for (var x = 0; x < val1.length; x++) {
                val = val1[x];
                console.log(val);
                if (val.indexOf(";") > -1) {
                    val = val.split(";");
                    for (let a = 0; a < val.length; a++) {
                        let keyP = val[a];
                        if (keyP.indexOf(":") > -1) {
                            keyP = keyP.split(":");
                            if (keyP.length > 0 && keyP.length < 3) {
                                if (keyP[0] === "text") {
                                    $("#" + id).text(keyP[1]);
                                } else {
                                    $("#" + id).css(keyP[0], keyP[1]);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $("#" + id).css("position", "absolute");
            $("#" + id).css("top", 50 * countDiv + 10);
        }
    });
}

//Creating dynamic link that automatically click
function downloadURI(uri, name) {
    var link = document.createElement("a");
    link.download = name;
    link.href = uri;
    console.log(uri);
    link.click();
    //after creating link you should delete dynamic link
    //clearDynamicLink(link); 
}

function printToFile(div) {
    html2canvas(div, {
        onrendered: function(canvas) {
            var myImage = canvas.toDataURL("image/png");
            //create your own dialog with warning before saving file
            //beforeDownloadReadMessage();
            //Then download file
            downloadURI("data:" + myImage, "yourImage.png");
        }
    });
}


function downloadImage(){
	var aDiv = document.getElementById("container_holder");
    printToFile(aDiv);
}

// render the image in our view
function renderImage(file) {

    // generate a new FileReader object
    var reader = new FileReader();

    // inject an image with the src url
    reader.onload = function(event) {
        the_url = event.target.result
        // $('#container').html('<img style="width:50%; height:100%;" src="' + the_url + '" />');
        $('#container_holder').append('<img style="top:0px; width:100%; height:100%;" src="' + the_url + '" />');
    }

    // when the file is read it triggers the onload event above.
    reader.readAsDataURL(file);
}

function clickPreTemp(a){
    console.log(a);
}

function fetchTemp(){
    $.ajax({
           url : "database.php", // the resource where youre request will go throw
           type : "GET", // HTTP verb
           success : function (res) {
              //in your case, you should return from the php method some fomated data that you  //need throw the data var object in param
              console.log(res);
                 //data = toJson(response) // optional
                //heres your code
                $("#temp-selector").empty();
                for(var x = 0; x < res.length; x++){
                    // console.log(res[x]);
                    var a = res[x];
                    $("#temp-selector").append('<img id="' + res[x].id + '" alt="' + x + '" src="upload/' + res[x].image + '" style="float:left; width:140px; height:140px; margin:5px;">');
                    $("#" + res[x].id).on("click", function(ev){
                        // console.log($(this).attr("alt"));
                        // console.log(res[$(this).attr("alt")]);
                        $('#container_holder').empty();
                        $('#container_holder').append('<img style="top:0px; width:100%; height:100%;" src="upload/' + res[$(this).attr("alt")].image + '" />');
                    });
                }
           },
           error : function(error, txt){
            console.log(txt);
           } 
        });
}


$(document).ready(function() {
    console.log("Ready jquery");
    fetchTemp();
    $("#aInput").change(function() {
        console.log("Input Changed");
        console.log(this.files);
        // grab the first image in the FileList object and pass it to the function
        renderImage(this.files[0])
    });
    $("#text-area-pane").submit(function(ev){
        ev.preventDefault();
        console.log("form submit called");
        // var data = $("#text-area-pane").serializeArray();
        var data1 = $("#text-area-pane").serialize();
        // console.log(JSON.stringify(data));
        // var a = JSON.stringify(data);

        console.log($("#aInput")[0].files[0]);
        var form_data = new FormData();
        form_data.append("file", $("#aInput")[0].files[0]);
        // if($("#aInput")[0].files[0] !== undefined) {
        //     form_data.append("file", $("#aInput")[0].files[0]);    
        // } else {
        //     alert("File is missing cannot save template");
        //     return false;
        // }
        form_data.append("data", data1);
        
        $.ajax({
           url : "database.php", // the resource where youre request will go throw
           type : "POST", // HTTP verb
           data : form_data,
           // dataType : "multipart/form-data",
           processData: false,
           contentType: false,
           success : function (response) {
              //in your case, you should return from the php method some fomated data that you  //need throw the data var object in param
              console.log("response");
              console.log(response);
                 //data = toJson(response) // optional
                //heres your code
           },
           error : function(error, txt){
            console.log(txt);
           } 
        });
        return false;

    });
});
</script>

<!-- text:!!! Hello World !!!;
left:20px;
top:50px;
color:white;
font-size:30px;

text:!!! abc@gmail.com !!!;
left:20px;
top:430px;
color:white;
font-size:30px;
background-color:grey; -->
</body>
</html>