#####IMPORT######
from shelper import SocketHelper
import json
import collections
import virtualbox #pyvbox

vbox = virtualbox.VirtualBox()


#####FONCTIONS######

#Renvoie la liste des vm d'un utilisateur au format json :
#"{"listing_vm": "iduser", "vm_x":  "nomvm" ...}" si la fonction trouve quelque chose
#"{"listing_vm": "iduser"}" si la fonction ne trouve aucune vm

def listingvm(id):

    vmlisting = collections.OrderedDict()  # Dictionnaire ordonne
    vmlisting['listing_vm'] = iduser #Premier element du dictionnaire
    # jsonlisting.append("1",iduser) #Ajout de l'info necessaire pour l'identification du json
    vmnb = 0  # nombre de vm avec l'id

    # Pour chaque vm
    for vm in vbox.machines:
        vmname = vm.name
        i = 0
        while vmname[i] != "_":  # Tant que le caractere a la position "i" n'est pas _ alors
            i += 1  # Incremente de 1 le compteur
        if iduser == vmname[:i]:  # Si on trouve une vm avec l'id
            vmnb += 1  # Incremente de 1 le nb de vm avec l'id
            vmlisting['vm_' + str(vmnb)] = vmname
    #Envoi des informations
    return vmlisting

#Renvoie la liste des vm et de leurs etats (en ligne, hors ligne)
#"{"state_vm": "1", "nomvm: "etat", ...}" Si la fonction trouve une vm et son etat (on/off)
#"{"state_vm": "1213"}" Sinon si la fonction ne trouve rien

def statevm(id):
    list1 = listingvm(id) #json
    vmstate = collections.OrderedDict()
    vmstate['state_vm'] = iduser
    for key, value in list1.iteritems():
        if 'vm_' in key: #S'il y a des vm
            vmfind = vbox.find_machine(value) #recupere les infos de la vm
            etat = vmfind.state #Recupere l'etat de la vm
            if str(etat) == "FirstOnline": #Si la vm est en ligne
                etat = "on"
                vmstate[value] = etat
            elif str(etat) == 'PoweredOff': #Si la vm est hors ligne
                etat = "off"
                vmstate[value] = etat
    return vmstate

#Creation du json et encodage en utf-8
def jsondata(raw):
    jdata = json.dumps(raw)
    jdata = jdata.decode('unicode-escape').encode('utf-8')  # Decode lunicode et rencode en utf8, a desactiver si le php ne le supporte pas
    return jdata


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

        iduser = python_obj['listing_vm']
        lvm = listingvm(iduser) #Appel de la fonction listingvm
        lvm = jsondata(lvm) #Appel de la fonction jsondata
        sockethelper.send_data(lvm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    elif 'state_vm' in data:

        iduser = python_obj["state_vm"]
        svm = statevm(iduser)
        svm = jsondata(svm) #Appel de la fonction jsondata
        sockethelper.send_data(svm) #Envoi des informations en json
        sockethelper.close_socket() #Fermeture de la socket

    else:
        sockethelper.send_data("Erreur")
        sockethelper.close_socket()