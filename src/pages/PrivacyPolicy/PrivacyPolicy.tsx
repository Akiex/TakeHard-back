// src/pages/PrivacyPolicy.jsx
import React from 'react';
import styles from "../../styles/LegalPage.module.scss";
const PrivacyPolicy = () => {
  return (
    <section className={styles.legalPage}>
      <h1>Politique de Confidentialité</h1>

      <p>
        Cette politique décrit comment TakeHardvantage collecte, utilise et protège vos données
        personnelles.
      </p>

      <h2>1. Données collectées</h2>
      <p>
        Nous collectons uniquement les données nécessaires à l’utilisation de l’application :
        prénom, email, données d'entraînement, etc.
      </p>

      <h2>2. Utilisation</h2>
      <p>
        Les données sont utilisées pour personnaliser votre expérience, améliorer l’application et
        assurer le bon fonctionnement des services.
      </p>

      <h2>3. Partage</h2>
      <p>
        Nous ne partageons pas vos données avec des tiers sans votre consentement, sauf obligation
        légale.
      </p>

      <h2>4. Sécurité</h2>
      <p>
        Vos données sont stockées de manière sécurisée. Des mesures sont prises pour empêcher tout
        accès non autorisé.
      </p>

      <h2>5. Vos droits</h2>
      <p>
        Conformément à la loi, vous disposez d’un droit d’accès, de rectification et de suppression
        de vos données personnelles.
      </p>

      <p>Dernière mise à jour : 21 mai 2025</p>
    </section>
  );
};

export default PrivacyPolicy;

