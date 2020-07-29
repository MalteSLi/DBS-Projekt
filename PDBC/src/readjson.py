import json

with open('covid19.json') as json_file:
    data = json.load(json_file)
    i = 0
    for rec in data['records']:
        print('countriesAndTerritories: ' + rec['countriesAndTerritories'])
        print('date: ' + rec['day'] + "/" + rec['month'] + "/" + rec['year'])
        print('cases: ' + rec['cases'])
        print('deaths: ' + rec['deaths'])
        print('')
        i += 1
        if i > 10:
            break