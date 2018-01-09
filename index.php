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
        <div class="form-group admin-only">
            <label for="aInput">Select File Input</label>
            <input type="file" name="file" class="form-control-file" id="aInput">
        </div>
        <button type="button" class="btn btn-primary admin-only" onclick="addDiv()">Add Pane</button>

        <div id="container" style="width: 100%; height: 500px; padding:10px;">
            <div id="container_holder" style="position:relative; width:100%; height:100%; box-shadow: 0px 0px 5px grey; background-color:#e5e5e5; display: inline-block;"></div>     
        </div>
        <button type="button" class="btn btn-success" onclick="downloadImage()">Download Image</button>
    </div>
    <div class="col-md-3">
        <div  style=" box-shadow: 0px 0px 5px grey; margin-right: 10px; width:100%; height:100%; min-height:1000px; padding:10px;">
            <form id="text-area-pane">
                <button type="submit" name="submit" class="btn btn-default admin-only" >Save Template</button>
            </form>
        </div>    
    </div>
</div>

<script type="text/javascript">
console.log("CORE READY");

var countDiv = 0;

var isAdmin = true;
var isEdit = false;

function addDiv() {
    isEdit = false;
    var date = Date.now();
    console.log("Add Div Called");
    $("#container_holder").append('<div style="" id="text-pane-' + date + '">hello</div>');
    $("#text-pane-" + date).css("position", "absolute");
    $("#text-pane-" + date).css("top", 0);
    // $("#text-pane-" + date).css("top", 50 * countDiv + 10);
    addCssTextArea("text-pane-" + date);
    countDiv++;
}

function addCssTextArea(id, val) {
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
            $("#" + id).css("top", 0);
            // $("#" + id).css("top", 50 * countDiv + 10);
        }
    });
    if(val !== undefined && val !== "") {
        $("#" + id + '-area').val(val);
    }
    
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
        // $('#container_holder').find("img").remove();
        $('#container_holder').empty();
        $('#container_holder').append('<img style="top:0px; width:100%; height:100%;" src="' + the_url + '" />');
    }
    // when the file is read it triggers the onload event above.
    reader.readAsDataURL(file);
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
                    if(res[x].image !== undefined && res[x].image !== ""){
                        $("#temp-selector").append('<img id="' + res[x].id + '" alt="' + x + '" src="upload/' + res[x].image + '" style="float:left; width:140px; height:140px; margin:5px;">');
                        $("#" + res[x].id).on("click", function(ev){
                            // console.log($(this).attr("alt"));
                            // console.log(res[$(this).attr("alt")]);
                            isEdit = true;
                            $('#container_holder').empty();
                            $('#container_holder').append('<img style="top:0px; width:100%; height:100%;" src="upload/' + res[$(this).attr("alt")].image + '" />');
                            processTemplate(res[$(this).attr("alt")].config, res[$(this).attr("alt")].id);
                        });
                    }
                }
           },
           error : function(error, txt){
            console.log(txt);
           } 
        });
}

function processTemplate(data, key){
    try {
        data  = JSON.parse(data);
        console.log(data);
        if(data.data !== undefined) {
            if(data.data.indexOf("=") > -1) {
                data = data.data.split("=");
                $("#container_holder").append('<div style="display:none; top:0px; position:absolute;" id="' + data[0] + '">hello</div>');
                data[1] = decodeURIComponent(data[1]);
                if(data[1].indexOf(";") > -1){
                    var cssS = data[1].split(";");
                    for(var z = 0; z < cssS.length; z++){
                        if(cssS[z].indexOf(":") > -1) {
                            cssS[z] = cssS[z].trim();
                            var qS = cssS[z].split(":");
                            if(qS[0] === "text"){
                                $("#" + data[0]).show();
                                $("#" + data[0]).text(qS[1]);
                                if(isAdmin) {
                                    userEditTextArea(data[0], data[1], key);
                                } else {
                                    userEditTextArea(data[0], qS[1]);
                                }
                            } else {
                                $("#" + data[0]).css(qS[0], qS[1]);
                            }
                        }
                    }
                }
            }
        }
    } catch(e) {
        console.log(e);
    }
}

function userEditTextArea(id, text, key){
    if(isAdmin){
        // TODO
        $("#text-area-pane").find("div.form-group").remove();
        $("#text-area-pane").append('<div class="form-group">' +
        '<label for="idKey">ID:</label>'
        + '<input class="form-control"  name="idKey" id="' + id + '-input" value="' + key + '" disabled />'
        + '</div>');
        addCssTextArea(id, text);
    } else {
        $("#text-area-pane").find("div.form-group").remove();
        $("#text-area-pane").append('<div class="form-group">' +
        '<label for="comment">' + id + ':</label>'
        + '<textarea class="form-control" rows="3" name="' + id + '" id="' + id + '-area" placeholder="' + id + '-area"></textarea>'
        + '</div>');
        $("#" + id + '-area').val(text);
        $("#" + id + '-area').on('change textInput input', function() {
            $("#" + id).text($(this).val());
        });
    }
}

$(document).ready(function() {
    console.info("JQ CORE ONLINE");
    fetchTemp();
    $("#aInput").change(function() {
        console.info("File Selected");
        isEdit = false;
        $("#text-area-pane").find("div.form-group").remove();
        // grab the first image in the FileList object and pass it to the function
        renderImage(this.files[0])
    });
    $("#text-area-pane").submit(function(ev){
        ev.preventDefault();
        console.info("Submit in progress...");
        var data1 = $("#text-area-pane").serialize();

        // console.log($("#aInput")[0].files[0]);
        var form_data = new FormData();
        if(!isEdit) {
            if($("#aInput")[0].files[0] !== undefined) {
                form_data.append("file", $("#aInput")[0].files[0]);    
            } else {
                alert("File is missing cannot save template");
                return false;
            }
        }
        if($("#text-area-pane").find("input").val() !== undefined) {
            form_data.append("id", parseInt($("#text-area-pane").find("input").val()));    
        }
        form_data.append("data", data1);
        $.ajax({
           url : "database.php", // the resource where youre request will go throw
           type : "POST", // HTTP verb
           data : form_data,
           processData: false,
           contentType: false,
           success : function (response) {
              //in your case, you should return from the php method some fomated data that you  //need throw the data var object in param
              console.info("Upload Success");
              fetchTemp();
           },
           error : function(error, res){
            console.error(res);
           } 
        });
        return false;
    });
    if(isAdmin) {
        adminOnly();
    } else {
        notAdmin();
    }
});

function notAdmin(){
    $(".admin-only").hide();
}
function adminOnly(){
    $(".admin-only").show();
}
</script>

<!-- 
text:!!! Hello World !!!;
left:20px;
top:50px;
color:white;
font-size:30px;

text:!!! abc@gmail.com !!!;
left:20px;
top:430px;
color:white;
font-size:30px;
background-color:grey;
-->
</body>
</html>