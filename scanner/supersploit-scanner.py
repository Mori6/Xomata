#webssploiter using utilities
#this one scans and exploits faster
#written by mori

import subprocess
import shlex
import sys

def scan_world():
    command = "zmap --bandwidth=10M --target-port=80 --max-targets=10000 --output-file=results.txt"
    execute = shlex(command)
    subprocess.check_output(execute)



try:
    subprocess.call("zmap")
except:
    print("error: zmap not installed")

scan_world(base_ip, subnet)

