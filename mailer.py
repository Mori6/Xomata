#example send an email using mailsac
#written by EvilUpgrades

import requests
url = "https://mailsac.com/api/outgoing-messages"
count = 0
while count < 50:
    from_email = "from@mailsac.com"
    big_msg = "msg"
    my_data = {
        '_mailsacKey': 'API_KEY',
        'to':'repicient@gmail.com',
        'from':from_email,
        'subject':'subject',
        'text':big_msg,
        'html':'<img src="image_url">',
    }
    send = requests.post(url, data=my_data)
    print(send.text)