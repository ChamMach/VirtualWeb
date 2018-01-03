#####IMPORT######
from shelper import SocketHelper
import json
import vmfonctions

#####PROGRAMME PRINCIPAL######

#Creation de la socket reseau
sockethelper = SocketHelper("172.31.0.50",1333)

#Tant que toujours vrai (afin d'etre toujours actif)
while True:

    sockethelper.s_accept() #On accepte toutes les connexions

    data = sockethelper.read_data() #On lis le flux entrant
    print data

    try: #Empeche le "crash" du serveur lorsque le flux entrant n'est pas du json
        python_obj = json.loads(data) #Chargement du json recu
        print python_obj
    except ValueError:
        print "Erreur pas de JSON obtenu"

    #Listing des vms pour un utilisateur
    if 'listing_vm' in data:

        iduser = python_obj['listing_vm']
        lvm = vmfonctions.listingvm(iduser) #Appel de la fonction listingvm
        lvm = vmfonctions.jsondata(lvm) #Appel de la fonction jsondata
        sockethelper.send_data(lvm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    elif 'infos_vm' in data:

        iduser = python_obj['infos_vm']
        ivm = vmfonctions.infosvm(iduser)
        ivm = vmfonctions.jsondata(ivm)
        print ivm
        sockethelper.send_data(ivm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    else:
        sockethelper.send_data("Erreur")
        sockethelper.close_socket()