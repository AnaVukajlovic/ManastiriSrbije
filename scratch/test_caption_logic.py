import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

def format_caption(caption):
    html_match = re.match(r'^(.*?)(?:<br\s*\/?>)?\s*<small[^>]*>(?:<em>)?(?:\*|\()?([^*()<>\n]+(?:\.rs|[a-zA-Z0-9\s\.\-_]+))(?:\*|\))?(?:<\/em>)?<\/small>$', caption, re.IGNORECASE)
    if html_match:
        desc = html_match.group(1).strip()
        source = html_match.group(2).strip()
    else:
        desc = caption
        source = ''
    return f'DESC: {desc}\nSOURCE: *{source}*'

caps = [
    'Crkva Svete Trojice u manastiru Bjele Vode kod Ljubovije sa zvonikom i uređenom portom<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>',
    'Zapadno pročelje sa drvenim ulaznim tremom i visokim kamenim zvonikom<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>',
    'Unutrašnjost hrama sa pozlaćenim polijelejem i drvoreznim ikonostasom<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>',
]

for c in caps:
    print(format_caption(c))
    print('-'*40)
