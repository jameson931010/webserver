import requests
import bs4
import re
import json
import sys

url = 'https://dictionary.cambridge.org/dictionary/english-chinese-traditional/'+'-'.join(sys.argv[1:]).replace("/", "-")
headers = {'User-Agent': 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'}
web_request = requests.get(url, headers=headers)
#web_request = requests.get(url)
res = bs4.BeautifulSoup(web_request.text, "html.parser")
#with open('out.html', 'w') as f:
#   print(res.prettify(), file=f)    # Python 3.x
soup = res.find('div', "di-body")   # The main part of definition
if(type(soup)==type(None)):
    print("No result")
    sys.exit(1)

pronounciation = list()
for pron in soup.find_all('span', ["dpos", "ipa"]):
    if(pron['class'].count("dpos")):
        pronounciation.append([pron.get_text()])
    else:
        pronounciation[-1].append(pron.get_text())


edef = None                         # Each entry, header include usage and english definition, body include chinese definition and example,\
                                    # and eg contains more example
cdef = None
example = list()
usage = list()
definition = list()
for sen in soup.find_all('div', ["def-body", "ddef_h", "phrase-head"]):
    if(sen['class']).count("phrase-head"):
        usage.append(sen.find('span', "phrase-title").get_text())
    if(sen['class'].count("ddef_h")):
        edef = sen.find('div', "ddef_d").get_text()
        for use in sen.find_all(True, ["gc", "usage", "usagenote"]):
            usage.append(use.get_text())
    if(sen['class'].count("def-body")):
        res = sen.find('span', "dtrans")
        if(type(res) == type(None) or "hdb" in res['class']):
            cdef = ""
        else:
            cdef = res.get_text()
        use = ""                # extracting phrases
        for ex in sen.find_all('span', ["eg", "gc"]):
            if(ex['class'].count("gc")):
                use += ex.get_text() + "; "
            if(ex['class'].count('eg')):
                example.append(ex.get_text())
                for u in ex.find_all('span', "b"):
                    use += ('+'+u.get_text())
                if(use):
                    usage.append([len(example), use[1:]])
                    use = ""

        definition.append({\
            "Chinese": cdef, "English": edef, "Example": example.copy(), "Usage": usage.copy()})
        edef = cdef = None
        example.clear()
        usage.clear()

more_example = list()
cnt = -1
for ex in soup.find_all(['h3', 'ul'], ["dsense_h", "daccord_b"]):
    if(ex['class'].count("dsense_h")):
        title = ex.find('span', "pos").get_text() + ": " + ex.find('span','guideword').span.get_text()
        more_example.append([title,[]])
        cnt += 1
    if(ex['class'].count("daccord_b")):
        if(cnt == -1 or more_example[cnt][1]):
            more_example.append(["",[]])
            cnt += 1
        for sen in ex.find_all('li', "eg"):
            more_example[cnt][1].append(sen.get_text())
more_example = [element for element in more_example if element[1]]

extend = list()                     # Extending applications
irregular =list()                   # For highlight
for word in soup.find_all('span', ["inf-group", "x-h", "x-p"]):
    extend.append(word.get_text())
    if(word['class'].count("inf-group")):
        irregular.append(word.find('b', "inf").get_text());

ans = {"Pronounciations": pronounciation, "Translates": definition, "Examples": more_example, "Extensions": extend, "Irregular": irregular}
print(json.dumps(ans, indent=4, ensure_ascii=False))
