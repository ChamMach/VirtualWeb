#####IMPORT######
from shelper import SocketHelper
import json
import vmfonctions
# coding: utf8

#####PROGRAMME PRINCIPAL######
ip = raw_input('Entrez l\'adresse IP de la machine : ')
#Creation de la socket reseau
sockethelper = SocketHelper(ip,1333)
print 'En attente ...'

#Tant que toujours vrai (afin d'etre toujours actif)
while True:

    sockethelper.s_accept() #On accepte toutes les connexions

    data = sockethelper.read_data() #On lis le flux entrant
    print 'Data recu : '
    print data

    try:
        python_obj = json.loads(data) #Chargement du json recu
    except ValueError:
        print "Erreur pas de JSON obtenu. Arret ..."

    #Listing des vms pour un utilisateur
    if 'listing_vm' in data:

        type = python_obj['listing_vm'] #all/id utilisateur
        lvm = vmfonctions.listingvm(type) #Appel de la fonction listingvm
        lvm = vmfonctions.jsondata(lvm) #Appel de la fonction jsondata
        print 'Json envoyee : '
        print lvm
        sockethelper.send_data(lvm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    elif 'infos_vm' in data:

        type = python_obj['infos_vm'] #all/id utilisateur
        ivm = vmfonctions.infosvm(type)
        ivm = vmfonctions.jsondata(ivm)
        print 'Json envoyee : '
        print ivm
        sockethelper.send_data(ivm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    elif 'start_vm' in data:
        type = python_obj['start_vm']
        svm = vmfonctions.startvm(type)
        svm = vmfonctions.jsondata(svm)
        print 'Json envoyee : '
        print svm
        sockethelper.send_data(svm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    elif 'stop_vm' in data:
        type = python_obj['stop_vm']
        stvm = vmfonctions.stopvm(type)
        stvm = vmfonctions.jsondata(stvm)
        print 'Json envoyee : '
        print stvm
        sockethelper.send_data(stvm)  # Envoi des informations en json
        sockethelper.close_socket()  # Fermeture de la socket

    elif 'create_vm' in data:
        nom = python_obj['create_vm']
        os = python_obj['os']
        ram = python_obj['ram']
        cpu = python_obj['cpu']
        sto = python_obj['sto']
        desc = python_obj['desc']
        create = vmfonctions.createvm(nom,os,ram,cpu,sto,desc)
        cvm = vmfonctions.jsondata(create)
        print 'Json envoyee : '
        print cvm
        sockethelper.send_data(cvm)  # Envoi des informations en json
        sockethelper.close_socket()  # Fermeture de la socket
    else:
        sockethelper.send_data("Erreur")
        sockethelper.close_socket()