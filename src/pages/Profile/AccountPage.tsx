import { useParams } from "react-router-dom";
import React, { useEffect, useState } from "react";
import { API_BASE_URL, API_ENDPOINTS } from "../../config/apiConfig";
import { Template } from "../../types/typesCard";
import styles from "./AccountPage.module.scss";
import UserTemplateCard from "./UserTemplateCard/UserTemplateCard";
import CreateTemplateForm from "../../components/CreateTemplateForm/CreateTemplateForm";
const AccountPage = () => {
  const { id } = useParams<{ id: string }>();
  const [templates, setTemplates] = useState<Template[]>([]);
  const [loading, setLoading] = useState(true);
  const [selected, setSelected] = useState<Template | null>(null);

  useEffect(() => {
    const fetchTemplates = async () => {
      try {
        const response = await fetch(
          `${API_BASE_URL}${API_ENDPOINTS.getTemplateByUser(Number(id))}`
        );
        if (!response.ok)
          throw new Error("Erreur lors du chargement des templates.");
        const data = await response.json();
        console.log("data", data);
        setTemplates(Array.isArray(data) ? data : []);
      } catch (error) {
        console.error("Erreur lors du chargement des templates :", error);
      } finally {
        setLoading(false);
      }
    };

    if (id) {
      fetchTemplates();
    }
  }, [id]);

  return (
    <div className={styles.containerAccountPage}>
      <h2>Profil de l'utilisateur</h2>

      <CreateTemplateForm
        userId={Number(id)}
        onCreated={(tpl) => setTemplates((prev) => [...prev, tpl])}
      />

      <section className={styles.templatesList}>
        {loading ? (
          <p>Chargement des templates...</p>
        ) : templates.length === 0 ? (
          <p>Aucun template trouvé pour cet utilisateur.</p>
        ) : (
          templates.map((template) => (
            <UserTemplateCard
              key={template.id}
              template={template}
              isSelected={selected?.id === template.id}
              onSelect={() =>
                setSelected((prev) =>
                  prev?.id === template.id ? null : template
                )
              }
            />
          ))
        )}
      </section>
    </div>
  );
};

export default AccountPage;
