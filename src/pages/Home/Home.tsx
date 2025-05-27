import React, { useState } from "react";
import CardWrapper from "../../components/Card/CardWrapper";
import ErrorBoundary from "./../../utils/ErrorBoundary";
import "./Home.module.scss";
const Home: React.FC = () => {
  return (
    <section className="home">
      <ErrorBoundary>
        <h2 className="title">Les derniers templates et exercices</h2>
        <article className="Templates">
          <h2>Templates à la une</h2>
          <CardWrapper type="templates" />
        </article>

        <article className="Exercices">
          <h2>Exercices à la une</h2>
          <CardWrapper type="exercices" />
        </article>
      </ErrorBoundary>
    </section>
  );
};

export default Home;
