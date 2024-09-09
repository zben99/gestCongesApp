@component('mail::message')
# Demande de congé refusée

Bonjour {{  $conge->employe->nom  }},

Votre demande de congé du {{ $conge->dateDebut }} au {{ $conge->dateFin }} a été refusée.

@component('mail::button', ['url' => url('/conges')])
Voir mes congés
@endcomponent

Merci de contacter votre responsable pour plus d'informations.

Cordialement,  
L'équipe RH

@endcomponent
