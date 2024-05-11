my_web_socket= new WebSocket('ws://localhost:8000');
UserName = document.getElementById("UserName");
content_div = document.getElementById('content')
send_button = document.getElementById('send_button')
message_input = document.getElementById('message')

console.log(message_input, send_button, content_div)
//______________________________________________________
var username = prompt("Please enter your name: ")
console.log(username);
UserName.innerHTML = `${username} 'S Chat App`
//______________________________________________________
my_web_socket.onopen = function () {
    data = {
        name: username,
        login : true
    }
    data = JSON.stringify(data)
    this.send(data)
    
}

my_web_socket.onmessage = function (event) {
    // console.log('message received', event.data )
    message_data = JSON.parse(event.data)
    content.innerHTML += `<p style="color:green;margin-top:30px">${message_data.content}</p>`

    
}
send_button.addEventListener('click', function (event) {
    chatMessage =message_input.value
    data = {
        name: username,
        body:chatMessage
    }
    my_web_socket.send(JSON.stringify(data))
    message_input.value=''
})
//____________________________________________________
my_web_socket.onerror= function (event) {
    content.innerHTML += `<h2 style="color:red;">Error !! Check your server settings</h2>`
}