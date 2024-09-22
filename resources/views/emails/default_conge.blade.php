<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre situation de congé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Votre situation de congé</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $employee->prenom }} {{ $employee->nom }},</p>

            <p>Nous tenions à vous informer de votre situation actuelle en matière de congés.</p>

            <p>Actuellement, vous avez cumulé est inférieur a 30 jours. Vous pouvez planifier vos prochaines périodes de repos en fonction de cette situation.</p>

            <p>Pour toute question relative à vos congés ou pour demander une période de congé, veuillez contacter votre responsable RH.</p>

            <p>Cordialement,</p>
            <p>L'équipe RH</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Votre Entreprise. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
