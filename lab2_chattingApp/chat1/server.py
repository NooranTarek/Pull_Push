from simple_websocket_server import WebSocketServer, WebSocket
import json

def prepare_message(message_content):
    print(message_content)
    message = {}
    username = message_content['name']
    if 'login' in message_content and message_content['login']:
        message = {"content": f'{username} has been connected'}
    elif 'body' in message_content and message_content['body']:
        message = {"content": f"{username}: {message_content['body']}"}
    #
    data = json.dumps(message)
    return data


class my_server(WebSocket):
    users = []
    user_with_name={}

    @classmethod
    def send_message_to_all(cls, message):
        for user in cls.users:
            user.send_message(message)

    def get_message_content(self, message):
        message = json.loads(message)
        return message

    def handle(self):
        received_message = self.get_message_content(self.data)
        msg_sent=prepare_message(received_message)
        self.__class__.send_message_to_all(msg_sent)
        # print(received_message)
        if 'login' in received_message and received_message['login']==True:
            self.username=received_message['name'] 
        # self.__class__.send_message_to_all(f"{received_message['name']} has been connected".capitalize())
        # for user in self.__class__.users:
        #     user.send_message(f"{received_message['name']} has been connected".capitalize())

    def connected(self):
        print(f"WebSocket connection started successfully -> {self}")
        self.__class__.users.append(self)

    def handle_close(self):
        print(self.address,'closed')
        message=  {"content": f'{self.username} has been disconnected'}
        self.__class__.send_message_to_all(json.dumps(message))
        self.__class__.clients.remove(self) 
        # self.__class__.send_message_to_all(f"{self.username} has been disconnected".capitalize())
        # self.__class__.users.remove(self)
        # for user in self.__class__.users:
        #     user.send_message(f"user has been disconnected")


if __name__ == '__main__':
    server = WebSocketServer('', 8000, my_server)
    print(server)
    server.serve_forever()
