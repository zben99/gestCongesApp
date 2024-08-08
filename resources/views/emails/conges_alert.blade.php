
<!DOCTYPE html>
<html>
<head>
    <title>Alerte de Congé</title>
</head>
<body>
    <h1>Alerte de Congé</h1>
    <p>Bonjour {{ $employe->nom }},</p>
    <p>Vous avez encore {{ $employe->reste }} jours de congé. N'oubliez pas de les utiliser avant la fin de l'année.</p>
    <p>Cordialement,</p>
    <p>Le Département RH</p>
</body>
</html>
