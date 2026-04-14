
import { GoogleGenAI } from "@google/genai";

// Fix: Initializing with process.env.API_KEY directly as per SDK requirements
const ai = new GoogleGenAI({ apiKey: process.env.API_KEY });

export const getStyleAdvice = async (occasion: string, userPreferences: string) => {
  try {
    // Fix: Using generateContent with model and prompt as per SDK guidelines
    const response = await ai.models.generateContent({
      model: 'gemini-3-flash-preview',
      contents: `Como especialista em semijoias da Analux, dê uma sugestão curta e luxuosa de quais acessórios usar para a ocasião: "${occasion}". 
      As preferências da cliente são: "${userPreferences}". 
      Foque na elegância da marca Analux (cores ouro, pérolas e cristais).`,
      config: {
        // Fix: Removed maxOutputTokens to follow guidelines: "Avoid setting this if not required" 
        // and to prevent potential blocked responses when thinkingBudget is not explicitly set.
        temperature: 0.7,
      }
    });
    // Fix: Using .text property instead of .text()
    return response.text;
  } catch (error) {
    console.error("Gemini Error:", error);
    return "Desculpe, nossa consultora de estilo está ocupada no momento. Tente novamente em breve!";
  }
};

export const generateCommunityWelcome = async (userName: string) => {
  try {
    // Fix: Using generateContent with model and prompt as per SDK guidelines
    const response = await ai.models.generateContent({
      model: 'gemini-3-flash-preview',
      contents: `Crie uma mensagem de boas-vindas curta e carinhosa para uma nova assinante da Analux chamada ${userName}. 
      Use um tom sofisticado e amigável.`,
      config: {
        // Fix: Removed maxOutputTokens to follow guidelines: "Avoid setting this if not required"
      }
    });
    // Fix: Using .text property instead of .text()
    return response.text;
  } catch (error) {
    return `Bem-vinda à comunidade Analux, ${userName}!`;
  }
};
