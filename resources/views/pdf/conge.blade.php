<!DOCTYPE html>
<html>
<head>
    <title>Document de Congé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
        }
        .logo {
            text-align: left;
            margin-bottom: 20px;
        }
        .signature {
            margin-top: 50px;
        }
        .date {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Logo de l'entreprise -->
    <div class="logo">
        <a class="app-logo" href="/"><img class="logo-icon me-2" src="{{ asset('images/logo-ONEA.jpg') }}" alt="logo"></a>
    </div>

    <!-- Texte personnalisé -->
    <div class="content">
        <p>Lieu : OUAGADOUGOU</p>
        <p class="date">Date : {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        
        <p>M./Mme {{ $conge->employe->nom }} {{ $conge->employe->prenom }},</p>
        
        <p>
            Je vous informe que l'entreprise reconnaît que vous bénéficiez de votre congé annuel, 
            votre période de repos commençant le {{ $conge->dateDebut }} et se terminant le {{ $conge->dateFin }}. 
            Vous devrez reprendre le travail le {{ \Carbon\Carbon::parse($conge->dateFin)->addDay()->format('d/m/Y') }} à l'heure prévue.
        </p>

        <p>
            Ce congé payé respecte les dispositions de la législation en vigueur et des accords collectifs applicables, 
            en veillant à ce que vous, en tant que salarié, ayez connaissance des dates de votre congé au moins 30 jours à l'avance.
        </p>
        
        <p>Veuillez signer cette lettre de notification.</p>

        <div class="signature">
            <p>Directeur des Ressources Humaines</p>
        </div>
    </div>
</body>
</html>
