#####IMPORT######
from shelper import SocketHelper
import json
import vmfonctions

#####PROGRAMME PRINCIPAL######

#Creation de la socket reseau
sockethelper = SocketHelper("192.168.1.11",1333)

#Tant que toujours vrai (afin d'etre toujours actif)
while True:

    sockethelper.s_accept() #On accepte toutes les connexions

    data = sockethelper.read_data() #On lis le flux entrant

    python_obj = json.loads(data) #Chargement du json recu

    #Listing des vms pour un utilisateur
    if 'listing_vm' in data:

        iduser = python_obj['listing_vm']
        lvm = vmfonctions.listingvm(iduser) #Appel de la fonction listingvm
        lvm = vmfonctions.jsondata(lvm) #Appel de la fonction jsondata
        sockethelper.send_data(lvm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    elif 'state_vm' in data:

        iduser = python_obj['state_vm']
        svm = vmfonctions.statevm(iduser)
        svm = vmfonctions.jsondata(svm) #Appel de la fonction jsondata
        sockethelper.send_data(svm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    elif 'infos_vm' in data:

        iduser = python_obj['infos_vm']
        ivm = vmfonctions.infosvm(iduser)
        ivm = vmfonctions.jsondata(ivm)
        sockethelper.send_data(ivm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    else:
        sockethelper.send_data("Erreur")
        sockethelper.close_socket()