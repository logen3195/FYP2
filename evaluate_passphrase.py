import spacy
import sys

# Ensure the script gets the passphrase from the command-line argument
if len(sys.argv) != 2:
    print("Error: Passphrase input not provided.")
    sys.exit(1)

passphrase = sys.argv[1]

# Load the spaCy model
nlp = spacy.load("en_core_web_sm")

def evaluate_passphrase(passphrase):
    feedback = []

    # Tokenize the passphrase
    doc = nlp(passphrase)

    # Check length
    if len(doc) < 4:
        feedback.append("Your passphrase is too short. Use at least 4 words.")

    # Check for numeric patterns
    if any(token.like_num for token in doc):
        feedback.append("Avoid using numbers in predictable patterns.")

    # Check word diversity
    unique_words = len(set(token.text.lower() for token in doc))
    if unique_words < len(doc):
        feedback.append("Your passphrase contains repeated words. Try adding variety.")

    # Strength evaluation
    if not feedback:
        strength = "Strong passphrase. Great job!"
    elif len(feedback) <= 2:
        strength = "Moderate passphrase. Some improvement needed."
    else:
        strength = "Weak passphrase. Needs significant improvement."

    feedback.append(f"Strength: {strength}")
    return feedback

# Run evaluation
feedback = evaluate_passphrase(passphrase)

# Output the feedback
print("\n".join(feedback))
