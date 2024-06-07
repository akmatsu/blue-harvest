from io import BytesIO

import cv2
import numpy as np
import open_clip
import torch
from fastapi import FastAPI, UploadFile
from PIL import Image

app = FastAPI()

# Load the model and tokenizer once when the server starts
MODEL_NAME = "ViT-B-32"
PRETRAINED_NAME = "laion2b_s34b_b79k"

device = torch.device("cuda" if torch.cuda.is_available() else "cpu")

model, _, preprocess = open_clip.create_model_and_transforms(
    MODEL_NAME, pretrained=PRETRAINED_NAME
)
model = model.to(device)
model.eval()
tokenizer = open_clip.get_tokenizer(MODEL_NAME)

threshold = 0.05  # Set your desired probability threshold here
number_of_tags = 10

tags = [
    "Parks",
    "Rivers",
    "Lakes",
    "Glaciers",
    "Mountains",
    "Trails",
    "animals",
    "animals",
    "Forests",
    "Wildlife",
    "Moose",
    "Bear",
    "Dog",
    "Cat",
    "Fox",
    "Wolf",
    "Aurora",
    "NorthernLights",
    "Snow",
    "Ice",
    "Spring",
    "Summer",
    "Fall",
    "Winter",
    "Sunrise",
    "Sunset",
    "Day",
    "Night",
    "Blue",
    "Red",
    "Green",
    "Yellow",
    "White",
    "Black",
    "Brown",
    "Orange",
    "Pink",
    "Purple",
    "Gold",
    "Silver",
    "Sky",
    "Water",
    "Land",
    "Bridge",
    "Road",
    "Highway",
    "Railroad",
    "Airport",
    "Farm",
    "Barn",
    "Tractor",
    "Hay",
    "Corn",
    "Wheat",
    "Market",
    "Festival",
    "Parade",
    "Fair",
    "Concert",
    "Event",
    "Celebration",
    "Ceremony",
    "Interview",
    "Meeting",
    "Workshop",
    "Conference",
    "Election",
    "Voting",
    "Ballot",
    "Mayor",
    "Governor",
    "Senator",
    "Representative",
    "Council",
    "Assembly",
    "Court",
    "Police",
    "Fire",
    "EMS",
    "Rescue",
    "School",
    "College",
    "Library",
    "Books",
    "History",
    "Culture",
    "Art",
    "Painting",
    "Sculpture",
    "Music",
    "Dance",
    "Theater",
    "Film",
    "Photography",
    "Portrait",
    "Landscape",
    "Wildlife",
    "Aerial",
    "Architecture",
    "Building",
    "House",
    "Cabin",
    "Church",
    "Hospital",
    "Store",
    "Mall",
    "Restaurant",
    "Cafe",
    "Bakery",
    "Hotel",
    "Motel",
    "Resort",
    "Campground",
    "Park",
    "Playground",
    "Picnic",
    "BBQ",
    "Fireworks",
    "Sports",
    "Football",
    "Basketball",
    "Baseball",
    "Soccer",
    "Hockey",
    "Skiing",
    "Snowboarding",
    "Fishing",
    "Fish",
    "Hunting",
    "Hiking",
    "Camping",
    "Boating",
    "Kayaking",
    "Canoeing",
    "Rafting",
    "Climbing",
    "Biking",
    "Running",
    "Walking",
    "Jogging",
    "Swimming",
    "Diving",
    "Skating",
    "Surfing",
    "WildlifeRefuge",
    "NatureReserve",
    "Garden",
    "FarmMarket",
    "Tree",
    "Flower",
    "Plant",
    "Leaf",
    "Rock",
    "Soil",
    "Sand",
    "Gravel",
    "Mud",
    "Clay",
    "Fossil",
    "Mineral",
    "Gem",
    "Crystal",
    "Volcano",
    "Wildfire",
    "Forest Fire",
    "Forest",
    "Earthquake",
    "Tsunami",
    "Flood",
    "Fire",
    "Storm",
    "Lightning",
    "Thunder",
    "Rain",
    "Drizzle",
    "Fog",
    "Wind",
    "Breeze",
    "Heat",
    "Cold",
    "Logo",
    "Icon",
    "Construction",
    "People",
    "Children",
    "Face",
]

face_cascade = cv2.CascadeClassifier(
    cv2.data.haarcascades + "haarcascade_frontalface_default.xml"
)


def detect_faces(image: Image.Image) -> bool:
    open_cv_image = np.array(image.convert("RGB"))[:, :, ::-1].copy()
    gray = cv2.cvtColor(open_cv_image, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, 1.1, 4)
    return len(faces) > 0


@app.post("/")
async def read_root(file: UploadFile):
    # Read the ImageFile
    image_data = await file.read()
    pil_image = Image.open(BytesIO(image_data))
    image = preprocess(pil_image).unsqueeze(0).to(device)

    # Tokenize the tags and move to the same device
    text = tokenizer(tags).to(device)

    with torch.no_grad(), torch.cuda.amp.autocast():
        image_features = model.encode_image(image)
        text_features = model.encode_text(text)
        image_features = image_features / image_features.norm(dim=-1, keepdim=True)
        text_features = text_features / text_features.norm(dim=-1, keepdim=True)
        text_probs = (100.0 * image_features @ text_features.T).softmax(dim=-1)

    top_tags = sorted(
        [
            (tag, prob.item())
            for tag, prob in zip(tags, text_probs[0])
            if prob >= threshold
        ],
        key=lambda x: x[1],
        reverse=True,
    )[:number_of_tags]

    # Check for specific tags
    contains_children = any(
        tag == "Children" for tag, prob in top_tags if prob >= threshold
    )
    contains_face = detect_faces(pil_image)

    # Filter out 'Children' and 'Face' tags from the top tags
    filtered_top_tags = [tag for tag, _ in top_tags if tag not in ["Children", "Face"]]

    return {"tags": filtered_top_tags, "flag": contains_children or contains_face}
