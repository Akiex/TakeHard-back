// src/pages/TermsOfUse.jsx
import React from 'react';
import styles from "../../styles/LegalPage.module.scss";
const TermsOfUse = () => {
  return (
    <section className={styles.legalPage}>
      <h1>Conditions Générales d'Utilisation</h1>

      <p>
        Bienvenue sur TakeHardvantage. En accédant à notre application, vous acceptez les présentes
        Conditions Générales d'Utilisation.
      </p>

      <h2>1. Objet</h2>
      <p>
        Les présentes conditions ont pour objet de définir les modalités d’utilisation des services
        proposés par TakeHardvantage.
      </p>

      <h2>2. Accès au service</h2>
      <p>
        L’accès à l’application est réservé aux utilisateurs inscrits. L’inscription est gratuite.
      </p>

      <h2>3. Responsabilités</h2>
      <p>
        L’utilisateur est responsable de l’exactitude des données qu’il fournit. TakeHardvantage
        ne saurait être tenue responsable en cas d’utilisation inappropriée.
      </p>

      <h2>4. Modifications</h2>
      <p>
        TakeHardvantage se réserve le droit de modifier les présentes conditions à tout moment. Les
        utilisateurs seront informés des mises à jour.
      </p>

      <p>Dernière mise à jour : 21 mai 2025</p>
    </section>
  );
};

export default TermsOfUse;

