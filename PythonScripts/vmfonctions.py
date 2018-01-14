import collections
import json
import virtualbox #pyvbox
import time

vbox = virtualbox.VirtualBox()

#####FONCTIONS######

#Renvoie la liste des vm d'un utilisateur au format json :
#"{"listing_vm": "iduser", "vm_x":  "nomvm" ...}" si la fonction trouve quelque chose
#"{"listing_vm": "iduser"}" si la fonction ne trouve aucune vm

def listingvm(type):
    vmlisting = collections.OrderedDict()  # Dictionnaire ordonne
    vmlisting['listing_vm'] = type #Premier element du dictionnaire
    vmnb = 0  # nombre de vm avec l'id
    if type == 'all': #Si le type est de type all alors on retourne toutes les vm
        for vm in vbox.machines:  # Pour chaque vm
            vmname = vm.name
            vmlisting['vm_' + str(vmnb)] = vmname
            vmnb += 1  # Incremente de 1 le nb de vm avec l'id
    else: #Sinon cela signifie que c'est un id alors on renvoie toutes les vm associees a cet id
        for vm in vbox.machines: # Pour chaque vm
            vmname = vm.name
            i = 0
            if '_' in vmname: #Si le caractere _ est present dans le nom de la vm
                while vmname[i] != "_":  # Tant que le caractere a la position "i" n'est pas _ alors
                    i += 1  # Incremente de 1 le compteur
            if type == vmname[:i]:  # Si on trouve une vm avec l'id
                vmnb += 1  # Incremente de 1 le nb de vm avec l'id
                vmlisting['vm_' + str(vmnb)] = vmname
    return vmlisting #Envoi des informations

#Creation du json et encodage en utf-8
def jsondata(raw):
    jdata = json.dumps(raw)
    jdata = jdata.decode('unicode-escape').encode('utf-8')  # Decode lunicode et rencode en utf8, a desactiver si le php ne le supporte pas
    return jdata

#Conversion bytes/Mo en octets et equivalent
#Retourne une valeur suivis de son suffixe
def convert(size, type):
    suffixes = 0
    if type == 'bytes':
        suffixes = ["octets", "Ko", "Mo", "Go", "To"]
    elif type == 'Mo':
        suffixes = ["Mo", "Go", "To"]
    tmpSize = size
    i = 0
    while (tmpSize >= 1024):
        tmpSize /= 1024.0
        i+=1
    tmpSize *= 100
    tmpSize /= 100
    tmpSize = format(tmpSize, '.2f') #Limitation a deux decimal apres la virgule
    return tmpSize, suffixes[i]

#Fonction qui concverti les Mo en bytes. Par exemple 25600Mo = 26843545600 bytes (octets)
#Retourne une valeur en bytes
def convertmegaoctetstobytes(size):
    tmpSize = size
    tmpSize *= 1024.0
    tmpSize *= 100
    tmpSize /= 100
    tmpSize *= 1024 #passer de kb en bytes (octets)
    tmpSize = format(tmpSize, '.0f') #pas de virgule
    return tmpSize

#Renvoie la liste des vm et de leurs caracteristiques (nom,description,os,cpu,ram,stockage logique, stockage reel)
#{"infos_vm": "123", "data": {"vm_1": {"nom": "", "description": "", "statut": "", "caracteristiques": {"os": "", "cpu":, "ram":, "sto_l":, "sto_r":}}}}
#"{"infos_vm": "123"}" Sinon si la fonction ne trouve rien

def infosvm(type):
    vmlisting = listingvm(type)
    vminfos = collections.OrderedDict()
    vminfos['infos_vm'] = type
    vmdata = collections.OrderedDict()
    vminfos['data']=""
    vmnb = 0  # nombre de vm avec l'id
    for key, value in vmlisting.iteritems():
        if 'vm_' in key: #S'il y a des vm
            vmnb+=1
            vm = collections.OrderedDict() #Creation d'un dictionnaire ordonnee pour cette vm
            vmcar = collections.OrderedDict()
            vmfind = vbox.find_machine(value)  #Recupere les infos de la vm
            vm['nom'] = vmfind.name
            vm['description'] = vmfind.description
            etat = vmfind.state  # Recupere l'etat de la vm
            if str(etat) == "FirstOnline":  # Si la vm est en ligne
                etat = "on"
                vm['statut'] = etat
            elif str(etat) == 'PoweredOff':  # Si la vm est hors ligne
                etat = "off"
                vm['statut'] = etat
            else: #Sinon
                etat = "inconnu"
                vm['statut'] = etat
            vmcar['os'] = vmfind.os_type_id
            vmcar['cpu'] = vmfind.cpu_count
            ramsize = vmfind.memory_size
            vmcar['ram'] = convert(ramsize,'Mo') #Conversion Mo en Go/To
            mediums = vmfind.medium_attachments
            sizehddlog = 0 #Taille du stockage logique sur la vm
            sizehddreal = 0 #Taille reel du stockage de la vm
            for med in mediums:
                type = med.type_p #Recupere le type du medium (hdd, cd ...)
                type = str(type) #Conversion en string
                if type == 'HardDisk':
                    sizehddlog = med.medium.logical_size + sizehddlog  # Totalite du stockage logique sur la vm, meme si plusieurs hdd
                    sizehddreal = med.medium.size + sizehddreal
            vmcar['sto_l'] = convert(sizehddlog,'bytes')
            vmcar['sto_r'] = convert(sizehddreal, 'bytes')
            vm['caracteristiques'] = vmcar #On ajoute les caracteristique de la vm
            vmdata['vm_' + str(vmnb)] = vm #On ajoute toutes les infos de la vm numero x dans le dictionnaire correspondant
    vminfos['data'] = vmdata
    return vminfos #Envoi des informations

#Demarre une VM en indiquant en parametre le nom de celle-ci
#Retourne un etat sur l'execution de cette fonction
def startvm(id):
    idvm = id
    vm = vbox.find_machine(idvm)
    infos = collections.OrderedDict()
    if str(vm.state) == 'FirstOnline': #Si la vm est lancee
        infos['start_vm'] = 'false_vmalreadyonline'
    elif str(vm.state) == 'PoweredOff': #Si la vm est a l'arret
        if str(vm.session_state) == 'Locked':  # Si la session est lock
            infos['start_vm'] = 'false_sessionlocked'
        elif str(vm.session_state) == 'Unlocked': #Si session debloque alors on lance la vm
            session = virtualbox.Session()
            vm.launch_vm_process(session, 'headless', '')  #Pas d'affiche de l'ecran lors du demarrage
            time.sleep(5) #Attente de cinq secondes
            if str(vm.state) == 'FirstOnline': #On reverifie
                infos['start_vm'] = 'true'
            else:
                infos['start_vm'] = 'false_startfailed'
        else:
            infos['start_vm'] = 'false_sessionunknown'
    else:
        infos['start_vm'] = 'false_statevmunknown'
    return infos

#Eteint une VM en indiquant en parametre le nom de celle-ci
#Retourne un etat sur l'execution de cette fonction
def stopvm(id):
    idvm = id
    vm = vbox.find_machine(idvm)
    infos = collections.OrderedDict()
    if str(vm.state) == 'PoweredOff':
        infos['stop_vm'] = 'false_vmalreadyoff' #VM deja eteinte
    elif str(vm.state) == 'FirstOnline':
        session = vm.create_session() #Creation d'une session, meme si une autre existe deja
        if str(vm.session_state) == 'Unlocked':  # Si la session est lock
            infos['stop_vm'] = 'false_sessionlocked'
        elif str(vm.session_state) == 'Locked':  # Si la session est lock
            session.console.power_down()  # Eteindre la vm
            time.sleep(5)  # Attente de cinq secondes
            if str(vm.state) == 'PoweredOff': #On reverifie
                infos['stop_vm'] = 'true'
            else:
                infos['stop_vm'] = 'false_stopfailed'
        else:
            infos['stop_vm'] = 'false_sessionunknown'
    else:
        infos['stop_vm'] = 'false_statevmunknown'
    return infos

#Cherche une VM en indiquant le nom de celle-ci
#Retourne la VM et tout ses attributs si elle existe, sinon 0
def vmfind(nom):
    name = nom
    try:
        vmfind = vbox.find_machine(name)
    except:
        vmfind = 0
    return vmfind

#Modifie un attribut d'une VM
#Retourne un etat sur l'execution de cette fonction
def modifyvm(nom,attribut,valeur):
    vmf = vmfind(nom) #On recupere la vm
    attr = attribut
    val = valeur
    infos = collections.OrderedDict()
    locktypevm = virtualbox.library.LockType(3) #Locktype de type vm
    if str(vmf.state) == 'PoweredOff': #Si la VM est hors-ligne
        with vmf.create_session(locktypevm) as session: #Creation d'une session
            sess = session.machine
            if attr == 'nom':
                sess.name = val
            if attr == 'ram':
                sess.memory_size = val
            if attr == 'cpu':
                sess.cpu_count = val
            if attr == 'desc':
                sess.description = val
            if attr == 'sto':
                hdd = virtualbox.library.DeviceType(3)  # harddisk
                stobytes = convertmegaoctetstobytes(val) #On converti les Mo en bytes
                mediums = sess.medium_attachments #Recupere la liste des mediums attache a la VM
                controller = mediums[0].controller #Recupere le controlleur de stockage (SATA/IDE) du premier medium (un hdd)
                controllerport = mediums[0].port #Recupere le port du premier medium (un hdd)
                device = mediums[0].device #Recupere le device du premier medium (un hdd)
                vmm = sess.get_medium(controller,controllerport,device) #On recupere le premier medium
                sess.detach_device(controller,controllerport,device) #On le detache de la VM temporairement
                resize = long(stobytes)
                vmm.resize(resize) #On resize le medium
                sess.attach_device(name=controller,controller_port=controllerport,device=device,type_p=hdd,medium=vmm) #On le reattache
            sess.save_settings()
            infos['modify_vm'] = 'true'
    elif str(vmf.state) == 'FirstOnline': #Si la VM est en ligne
        infos['modify_vm'] = 'false_vmonline'
    else: #Si la VM est dans un autre etat
        infos['modify_vm'] = 'false_vmstateunknown'
    return infos

#Clone une vm en direction d'une autre vm cree pour l'occasion (utilisation pour la fonction create_vm)
#Retourne un etat sur l'execution de cette fonction
def clonevm(vm,vmc): #vm,vmcible
    infos = collections.OrderedDict()
    lavm = virtualbox.library_ext.IMachine
    mode = virtualbox.library.CloneMode(3) #Clonage de la totalite de la VM
    opt = []
    lavm.clone_to(vm, target=vmc, mode=mode, options=opt) #Processus plus ou moins long suivant la VM que l'on clone
    vbox.register_machine(vmc) #On enregistre la vm cree dans virtualbox
    if vmfind(vmc.name) != 0: #Si la vm clonee existe
        infos['clone_vm'] = 'true'
    else:
        infos['clone_vm'] = 'false'
    return infos

#Creation d'une VM.
#Processus de creation : Creation d'une vm vierge, clonage d'une vm template dans cette VM vierge, modification des attributs de bases (optionnel, enregistrement de la VM clonee dans VirtualBox
#OS supporte : Windows7 (64 bits), Centos (64 bits)
#Retourne un etat sur l'execution de cette fonction
def createvm(nom,os,ram,cpu,sto,desc):
    arr = []
    infos = collections.OrderedDict()
    if vmfind(nom) == 0: #On verifie que la vm n'existe pas (avec cet id utilisateur)
        if os == 'Windows7_64':
            vm = vbox.find_machine('0_Windows7') #Recherche de la vm template
            nomt = '0_Windows7' + nom #nom temporaire (afin d'eviter le bug lors du clonage complet de la vm)
            vmc = vbox.create_machine(name=nomt,groups=arr, os_type_id='Windows7_64', flags='/', settings_file='')
            clone = clonevm(vm,vmc)
            if clone['clone_vm'] == 'true':
                try:
                    modifyvm(nomt, 'nom', nom)  # Renommage de la vm avec le nom choisi par l'utilisateur
                    if ram != 2048:
                        modifyvm(nom, 'ram', ram)
                    if cpu != 1:
                        modifyvm(nom, 'cpu', cpu)
                    if sto != 25600:  # 25600Mo = 25Go
                        modifyvm(nom, 'sto', sto)
                    if desc != '':
                        modifyvm(nom, 'desc', desc)
                except:
                    infos['create_vm'] = 'false_modifyvmfailed'
            else:
                infos['create_vm'] = 'false_cloningfailed' #Probleme lors du clonage de la vm template
        elif os == 'RedHat_64':
            vm = vbox.find_machine('0_Centos')  # Recherche de la vm template
            nomt = '1_Centos' + nom  # nom temporaire (afin d'eviter le bug lors du clonage complet de la vm)
            vmc = vbox.create_machine(name=nomt, groups=arr, os_type_id='RedHat_64', flags='/', settings_file='')
            clone = clonevm(vm, vmc)
            if clone['clone_vm'] == 'true':
                try:
                    modifyvm(nomt, 'nom', nom)  # Renommage de la vm avec le nom choisi par l'utilisateur
                    if ram != 1024:
                        modifyvm(nom,'ram', ram)
                    if cpu != 1:
                        modifyvm(nom,'cpu', cpu)
                    if sto != 8192:  # 8192Mo = 8Go
                        print 'ok'
                        modifyvm(nom,'sto', sto)
                    if desc != '':
                        modifyvm(nom,'desc', desc)
                    infos['create_vm'] = 'true'
                except:
                    infos['create_vm'] = 'false_modifyvmfailed'
        else:
            infos['create_vm'] = 'false_osunknown' #OS inconnu du programme python
    else:
        infos['create_vm'] = 'false_vmalreadyexist' #La vm existe deja
    return infos