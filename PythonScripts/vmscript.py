#####IMPORT######
from shelper import SocketHelper
import json
import collections
import virtualbox #pyvbox

vbox = virtualbox.VirtualBox()

#####PROGRAMME PRINCIPAL######

#Creation de la socket reseau
sockethelper = SocketHelper("192.168.1.11",1333)

#Tant que toujours vrai (afin d'etre toujours actif)
while True:

    sockethelper.s_accept() #On accepte toutes les connexions

    data = sockethelper.read_data() #On lis le flux entrant

    python_obj = json.loads(data) #Chargement du json recu
    #print data

    #Listing des vms pour un utilisateur
    if 'listing_vm' in data:

        iduser = python_obj["listing_vm"]
        jsonlisting={}
        jsonlisting= collections.OrderedDict() #Dictionnaire ordonne
        jsonlisting['listing_vm'] = iduser
        #jsonlisting.append("1",iduser) #Ajout de l'info necessaire pour l'identification du json
        vmnb = 0 #nombre de vm avec l'id

        # Pour chaque vm
        for vm in vbox.machines:
            vmname=vm.name
            i=0
            while vmname[i] != "_": #Si le caractere numero x du nom est egal a _ alors
                i+=1 #Incremente de 1 le compteur
            if iduser == vmname[:i]: #Si on trouve une vm avec l'id
                vmnb+=1 #Incremente de 1 le nb de vm avec l'id
                jsonlisting["vm_"+str(vmnb)] = vmname

        jsondata=json.dumps(jsonlisting)
        jsondata=jsondata.decode('unicode-escape').encode('utf-8') #Decode lunicode et rencode en utf8, a desactiver si le php ne le supporte pas
        sockethelper.send_data(jsondata) #Envoi des informations en json
        sockethelper.close_socket

    else:
        sockethelper.send_data("Erreur")
        sockethelper.close_socket