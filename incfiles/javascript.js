var atno = 1;
var options = 3;
var XMLHttpFactories = [
    function () {return new XMLHttpRequest()},
    function () {return new ActiveXObject("Msxml2.XMLHTTP")},
    function () {return new ActiveXObject("Msxml3.XMLHTTP")},
    function () {return new ActiveXObject("Microsoft.XMLHTTP")}
];

function wrapText(elementID, openTag, closeTag) {
    var textArea = document.getElementById(elementID);
    var len = textArea.value.length;
    var start = textArea.selectionStart;
    var end = textArea.selectionEnd;
    var selectedText = textArea.value.substring(start, end);
    var replacement = openTag + selectedText + closeTag;
    textArea.value = textArea.value.substring(0, start) + replacement + textArea.value.substring(end, len);
    textArea.focus();
    textArea.selectionStart = textArea.selectionEnd = end + openTag.length + closeTag.length;
}

function addText(elementID, tag) {
    var textArea = document.getElementById(elementID);
    var len = textArea.value.length;
    var insertposition = textArea.selectionEnd;
    textArea.value = textArea.value.substring(0, insertposition) + tag + textArea.value.substring(insertposition, len);
    textArea.focus();
    textArea.selectionStart = textArea.selectionEnd = insertposition + tag.length;
}

function toEnd(elementID)
    {
        var textArea = document.getElementById(elementID);
        textArea.focus();
        textArea.selectionStart = textArea.selectionEnd = textArea.value.length;
    }

function addAttachment(){
    if (atno < 5){
        $("#attachments").prepend('<input type="file" size="48" name="attachment"><br>');
        atno++;
        return 1;
    } else return alert("No more attachments allowed!");
}

function showpreview(data){
    var preview = $("#preview");
    preview.empty();
    title = $('title', data).text();
    body = $('body', data).text();
    preview.html("Preview<br><table border='1px'><tr><td>"+title+"</td></tr><tr><td>"+body+"</td></tr></table>");
}

function previewclick(){
    request = $.post(url="/previewpost", {
        title: $('#postformtitle').val(),
        body: $('#body').val()
    }, function(data){
        showpreview(data);
    });
}

function addOption(){
    options = options+1;
    $("#options").append("<label for='option"+options+"'> Option "+options+":</label> <input name='option"+options+"' type='text'><br>");
}

$(function(){
    $('.oldattachment').each(function(){
        var current = this;
        var par = ($(current)).parent()
        var attachment = $(current).attr("id")
        var session = $("#session").val()
        this.onclick = function(event) {
            //alert("Are you sure?");
            ($(current)).detach();
            ($(par)).append('Removing Attachment');
            $.getJSON(url="/removeattachment", {
                attachment: attachment,
                session: session
            }, function(data){
                if (data){
                    ($(par)).detach();
                    atno -= 1;
                }
            });
        };
    });
});

function sendRequest(url,callback,postData) {
    var req = createXMLHTTPObject();
    if (!req) return 'No request';
    var method = (postData) ? "POST" : "GET";
    req.open(method,url,true);
    if (postData)
        req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    req.onreadystatechange = function () {
        if (req.readyState != 4) return 'State not 4';
        if (req.status != 200 && req.status != 304) {
            return 'Status 204';
        }
        callback(req);
    }
    if (req.readyState == 4) return 'State 4';
    req.send(postData);
}

function createXMLHTTPObject() {
    var xmlhttp = false;
    for (var i=0;i<4;i++) {
        try {
            xmlhttp = XMLHttpFactories[i]();
        }
        catch (e) {
            continue;
        }
        break;
    }
    return xmlhttp;
}

function handleQuote(req){
    addText("body", req.responseText);
    document.getElementById("body").focus();
}

function quotePost(post, session) {
    var requesturl = '/getpost?post=' + post + '&session=' + session;
    sendRequest(requesturl, handleQuote);
}