<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat de Location - {{ $contrat->number_contrat }}</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .print-button {
                display: none;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #059669;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.2;
            color: #000;
            font-size: 10px;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .header-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 0 0 15px 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .section-title {
            font-size: 10px;
            font-weight: bold;
            margin: 12px 0 8px 0;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }
        
        .party-section {
            margin: 10px 0;
        }
        
        .party-label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 9px;
        }
        
        .form-field {
            min-width: 200px;
            margin: 2px 0;
            padding: 1px 3px;
            height: 16px;
            border: 1px solid #000;
            display: inline-block;
            font-size: 9px;
        }
        
        .separator {
            font-size: 9px;
            margin: 8px 0;
        }
        
        .conditions {
            margin: 10px 0;
            font-size: 8px;
        }
        
        .conditions p {
            margin: 3px 0;
        }
        
        .conditions ol {
            margin: 3px 0;
            padding-left: 15px;
        }
        
        .conditions li {
            margin: 2px 0;
        }
        
        .footer {
            margin-top: 15px;
            font-size: 8px;
            text-align: center;
        }
        
        .contract-info {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 3px solid #007bff;
            font-size: 8px;
        }
        
        .contract-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 8px 0;
        }
        
        .detail-group {
            margin: 3px 0;
        }
        
        .detail-label {
            font-weight: bold;
            font-size: 8px;
        }
        
        .detail-value {
            font-size: 8px;
        }
        
        .equipment-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            margin: 8px 0;
        }
        
        .equipment-item {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 8px;
        }
        
        .equipment-checkbox {
            width: 8px;
            height: 8px;
            border: 1px solid #000;
            display: inline-block;
            line-height: 8px;
            text-align: center;
            font-size: 6px;
        }
        
        .transition-text {
            font-style: italic;
            margin: 8px 0;
            text-align: center;
            font-size: 9px;
        }
        
        .signature-section {
            margin-top: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .signature-box {
            border-top: 1px solid #000;
            padding-top: 5px;
            text-align: center;
            font-size: 8px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Ensure everything fits on one page */
        .main-content {
            max-height: 25cm;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()" style="right: 20px;">
        🖨️ Imprimer le Contrat
    </button>
    <button class="print-button" onclick="window.history.back()" style="right: 200px; background: #6b7280;">
        ← Retour
    </button>
    
    <div class="main-content">
        <h1 class="header-title">CONTRAT DE LOCATION DE VOITURE ENTRE PARTICULIERS</h1>
        
        <div class="contract-info">
            <h3 style="margin: 0 0 5px 0; font-size: 9px;">Informations du contrat</h3>
            <div class="contract-details">
                <div class="detail-group">
                    <span class="detail-label">Numéro de contrat:</span>
                    <span class="detail-value">{{ $contrat->number_contrat }}</span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ $contrat->date_contrat ? $contrat->date_contrat->format('d/m/Y') : 'Non définie' }}</span>
                </div>
            </div>
        </div>

        <div class="party-section">
            <div class="party-label">Entre les soussignés :</div>
            <div style="margin-top: 5px;">
                <span class="form-field" style="width: 300px;">{{ $contrat->clientOne->nom }} {{ $contrat->clientOne->prenom }}</span>
                <span style="margin-left: 10px; font-size: 8px;">ci-après désigné(e) « <strong>Le Propriétaire</strong> » d'une part,</span>
            </div>
        </div>

        <div class="party-section">
            <div style="margin-top: 5px;">
                <span class="form-field" style="width: 300px;">{{ $contrat->clientTwo ? $contrat->clientTwo->nom . ' ' . $contrat->clientTwo->prenom : '________________' }}</span>
                <span style="margin-left: 10px; font-size: 8px;">ci-après désigné(e) « <strong>Le Locataire</strong> » d'autre part,</span>
            </div>
        </div>

        <div class="transition-text">Ci-après dénommées ensemble « <strong>LES PARTIES</strong> ».</div>

        <div class="section-title">Article 1 - Objet du contrat</div>
        <p style="margin: 5px 0; font-size: 8px;">
            Le présent contrat a pour objet la location du véhicule suivant :
        </p>
        <div style="margin: 5px 0;">
            <span class="detail-label">Marque:</span>
            <span class="form-field" style="width: 150px;">{{ $contrat->vehicule->marque->marque }}</span>
        </div>
        <div style="margin: 5px 0;">
            <span class="detail-label">Modèle:</span>
            <span class="form-field" style="width: 150px;">{{ $contrat->vehicule->name }}</span>
        </div>
        <div style="margin: 5px 0;">
            <span class="detail-label">Immatriculation:</span>
            <span class="form-field" style="width: 150px;">{{ $contrat->vehicule->immatriculation }}</span>
        </div>
        <div style="margin: 5px 0;">
            <span class="detail-label">Description:</span>
            <span class="form-field" style="width: 300px;">{{ $contrat->description ?? 'Véhicule en bon état' }}</span>
        </div>

        <div class="section-title">Article 2 - Durée et conditions</div>
        <p style="margin: 5px 0; font-size: 8px;">
            La location est consentie pour une durée de <span class="form-field" style="width: 100px;">{{ $contrat->duree ?? '___' }}</span> jours,
            à compter du <span class="form-field" style="width: 100px;">{{ $contrat->date_contrat ? $contrat->date_contrat->format('d/m/Y') : '___/___/____' }}</span>.
        </p>

        <div class="section-title">Article 3 - Prix</div>
        <p style="margin: 5px 0; font-size: 8px;">
            Le prix de la location est fixé à <span class="form-field" style="width: 100px;">{{ $contrat->prix ? number_format($contrat->prix, 2) . ' DH' : '___ DH' }}</span> par jour,
            soit un total de <span class="form-field" style="width: 100px;">{{ $contrat->total_ttc ? number_format($contrat->total_ttc, 2) . ' DH' : '___ DH' }}</span> TTC.
        </p>

        <div class="section-title">Article 4 - Équipements inclus</div>
        <div class="equipment-list">
            <div class="equipment-item">
                <span class="equipment-checkbox">□</span>
                <span>Clés du véhicule</span>
            </div>
            <div class="equipment-item">
                <span class="equipment-checkbox">□</span>
                <span>Carte grise</span>
            </div>
            <div class="equipment-item">
                <span class="equipment-checkbox">□</span>
                <span>Assurance</span>
            </div>
            <div class="equipment-item">
                <span class="equipment-checkbox">□</span>
                <span>Pneus de secours</span>
            </div>
            <div class="equipment-item">
                <span class="equipment-checkbox">□</span>
                <span>Triangle de signalisation</span>
            </div>
            <div class="equipment-item">
                <span class="equipment-checkbox">□</span>
                <span>Gilet de sécurité</span>
            </div>
        </div>

        <div class="section-title">Article 5 - Conditions générales</div>
        <div class="conditions">
            <ol>
                <li>Le locataire s'engage à utiliser le véhicule conformément à sa destination et à respecter le code de la route.</li>
                <li>Le locataire est responsable des dommages causés au véhicule pendant la durée de la location.</li>
                <li>Le véhicule doit être restitué dans l'état où il a été reçu, compte tenu de l'usure normale.</li>
                <li>En cas de retard dans la restitution, des frais supplémentaires pourront être facturés.</li>
                <li>Le propriétaire déclare être propriétaire du véhicule et avoir le droit de le louer.</li>
            </ol>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <strong>Le Propriétaire</strong><br>
                Signature et cachet
            </div>
            <div class="signature-box">
                <strong>Le Locataire</strong><br>
                Signature
            </div>
        </div>

        <div class="footer">
            <p>Contrat établi le {{ $contrat->date_contrat ? $contrat->date_contrat->format('d/m/Y') : '___/___/____' }} - 
            Document généré automatiquement</p>
        </div>
    </div>
</body>
</html>
