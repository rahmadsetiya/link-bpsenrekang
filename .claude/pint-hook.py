import sys, json, subprocess, re

d = json.load(sys.stdin)
f = d.get('tool_input', {}).get('file_path', '')
if f and re.search(r'\.php$', f, re.I):
    subprocess.run(['php', 'vendor/bin/pint', f], capture_output=True)
