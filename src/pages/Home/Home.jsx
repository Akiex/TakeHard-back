import Button from "../../components/Button/Button";
import CardWrapper from "../../components/Card/CardWrapper";
import { useState } from "react";

const Home = () => {
  const [selectedTemplate, setSelectedTemplate] = useState(null);
  const handleClick = () => {
    alert("Bouton cliqué !");
  };

  return (
<>
      <section className="Templates">
        <h2>Templates à la une</h2>
        <CardWrapper
          selectedTemplate={selectedTemplate}
          setSelectedTemplate={setSelectedTemplate}
        />
      </section>

      <section className="Exercices">
        <h2>Exercices à la une</h2>
        <CardWrapper
          selectedTemplate={selectedTemplate}
          setSelectedTemplate={setSelectedTemplate}
        />
      </section>
    </>

  );
};

export default Home;
