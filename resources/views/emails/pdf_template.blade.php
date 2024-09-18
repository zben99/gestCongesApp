<!-- resources/views/emails/pdf_template.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Alerte de congé</title>
</head>
<body>
    <h1>Bonjour {{ $employee->name }},</h1>
    <p>Vous avez {{ $employee->conge_restant }} jours de congé restants.</p>
    <p>Veuillez prendre vos dispositions avant l'épuisement de vos jours de congé.</p>
</body>
</html>
