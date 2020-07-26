# DBS-Projekt
Ein Projekt des Moduls "Datenbanksysteme SS20" über die Derwaltung und Darstellung von Covid-19 Daten

Ich habe in pgAdmin4 eine neue Datenbank namens "dbs_project_covid19" erstellt, in der ich die Daten speichern werde.
Diese Datebank nutzt jetzt natürlich PostgreSQL.
Am besten macht ihr es genauso, damit wir nicht ständig den Code anpassen müssen.
Passwort und Nutzname müsst ihr auf euch anpassen.

HelloWorld.py ist ein Programm zum Überprüfen ob man überhaupt eine Verbindung zu seiner DB aufbauen kann.
Das Programm gibt in der Konsole nur die Version der DB aus.

Beide Python Programme funktionieren nun. Wichtig ist zuerst die processCovid19json.py auszuführen und danach erst die processOWIDjson.py,
weil in der ersten Datei die Tabellen erzeugt werden.

Außerdem habe ich die Tabellen für in der DB geändert: Population ist nun bei Country mit drin und nicht mehr in DayData. Das habe ich gemacht, weil mir aufgefallen ist,
dass an jedem Tag in einem Land die Population gleich ist. Macht also bei Country mehr Sinn finde ich.

TODO: ERM und Relationales Modell müssen entsprechend der oberen Änderung angepasst werden, wenn ihr einverstanden seid.