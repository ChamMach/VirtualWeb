# Vmscript

## Prérequis

1. Etre sur un système d'exploitation Windows (testé sous Windows 10)
2. Avoir VirtualBox d'installé et fonctionnel
3. Autoriser le port 1333 dans le pare-feu de Windows

### Utilisation

1. Télécharger le fichier vmscript.rar
2. Extraire le dossier
3. Exécuter vmscript.exe

### Appel des fonctions inclues dans le programme

Les fonctions suivantes sont peuvent-être appelées avec n'importe quel langage de programmation supportant l'envoi d'un flux json à une socket réseau ouverte par le programme python vmscript.exe (localhost/@ IP distante, port 1333)

#### *listing_vm*

Fonction permettant de lister un ensemble de VM appartenant à un utilisateur *ou* à l'ensemble des VM présentes sur l'infrastructure VirtualBox.

La valeur associé à la clé json 'listing_vm' doit être "all" (retourner toutes les VM) ou un id utilisateur.

Si la fonction retrouve des occurences dans le VirtualBox auquel le programme vmscript.exe est exécuté, alors le resultat retourné (en format json) sera de la forme suivante :

Avec utilisation du type "all" :

```
"{"listing_vm": "all", "vm_0": "123_Développement", "vm_1": "123_Baptiste", "vm_2": "123_ChamMach", "vm_3": "test", "vm_4": "123_Centos"}"
```

Avec utilisation d'un id utilisateur existant :

```
"{"listing_vm": "123", "vm_1": "123_Développement", "vm_2": "123_Baptiste", "vm_3": "123_ChamMach", "vm_4": "123_Centos"}"
```

:information_source: On remarque que la vm "test" n'est plus présente car non associé à l'utilisateur ayant l'id 123.

Si aucune machine virtuelles n'est trouvé, le retour sera de la forme :

```
"{"listing_vm": "1234"}"
```

#### *infos_vm*

Fonction permettant de lister des caractéristiques propres à la totalité des VM appartenant à un utilisateur *ou* à l'ensemble des VM présentes sur l'infrastructure VirtualBox.

Liste des caractéristiques par vm :

- nom
- description
- status (on/off/inconnu)
- os
- cpu
- ram
- taille du stockage logique
- taille du stockage réel


La valeur associé à la clé json 'infos_vm' doit être "all" (retourner toutes les infos de toutes les VM) ou un id utilisateur.

Si la fonction retrouve des occurences dans le VirtualBox auquel le programme vmscript.exe est exécuté, alors le resultat retourné (en format json) sera de la forme suivante :

{"**infos_vm**": "all/id", "**data**": {"vm_1": {"**nom**": "nom", "**description**": "description", "**statut**": "on/off/inconnu", "**caracteristiques**": {"**os**": "os", "**cpu**": nb, "**ram**": ["nb", "unit (Mo/Go/To)"], "**sto_l**": ["nb", "unit (octets/Mo/Go/To)"], "**sto_r**": ["nb", "unit (octets/Mo/Go/To)"]}}}}

Exemple avec l'id 123 :

```
{"infos_vm": "123", "data": {"vm_1": {"nom": "123_Développement", "description": "test vm 1 2 3 4 5", "statut": "off", "caracteristiques": {"os": "Windows7_64", "cpu": 2, "ram": ["2.96", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}, "vm_2": {"nom": "123_Baptiste", "description": "", "statut": "off", "caracteristiques": {"os": "RedHat", "cpu": 1, "ram": ["2.00", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}, "vm_3": {"nom": "123_ChamMach", "description": "", "statut": "off", "caracteristiques": {"os": "MacOS106", "cpu": 1, "ram": ["2.00", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}, "vm_4": {"nom": "123_Centos", "description": "", "statut": "off", "caracteristiques": {"os": "RedHat_64", "cpu": 1, "ram": ["1.00", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}}}
```

Exemple avec le type "all" :

```
{"infos_vm": "all", "data": {"vm_1": {"nom": "123_Développement", "description": "test vm 1 2 3 4 5", "statut": "off", "caracteristiques": {"os": "Windows7_64", "cpu": 2, "ram": ["2.96", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}, "vm_2": {"nom": "123_Baptiste", "description": "", "statut": "off", "caracteristiques": {"os": "RedHat", "cpu": 1, "ram": ["2.00", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}, "vm_3": {"nom": "123_ChamMach", "description": "", "statut": "off", "caracteristiques": {"os": "MacOS106", "cpu": 1, "ram": ["2.00", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}, "vm_4": {"nom": "test", "description": "", "statut": "off", "caracteristiques": {"os": "Windows7_64", "cpu": 1, "ram": ["2.00", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}, "vm_5": {"nom": "123_Centos", "description": "", "statut": "off", "caracteristiques": {"os": "RedHat_64", "cpu": 1, "ram": ["1.00", "Go"], "sto_l": ["0.00", "octets"], "sto_r": ["0.00", "octets"]}}}}
```

*Suite du Readme en cours d'écriture*
