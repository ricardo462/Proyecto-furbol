import pandas as pd  
PATH = r'C:\Users\Ricardo\Desktop\Universidad\8vo semestre\Base de datos\Hitos\Proyecto-furbol\Main\Datos\shots.csv'
teams = ['teamID', 'name']
appearance = ['playerID', 'gameID', 'substituteIn', 'time', 'substituteOut', 'goals', 'ownGoals', 'shots', 'assists', 'keyPasses', 'position', 'positionOrder', 'yellowCard', 'redCard']
games = ['gameID', 'leagueID', 'season', 'date', 'homeGoals', 'awayGoals', 'homeGoalsHalfTime', 'awayGoalsHalfTime']
players = ['playerID', 'name']  
shots = ['shooterID', 'assisterID', 'gameID', 'minute', 'positionX', 'positionY', 'shotType', 'shotResult', 'lastAction', 'situation']
teams = ['teamID', 'name']
teamStats = ['teamID', 'gameID', 'shotsOnTarget', 'shots', 'goals', 'location', 'deep', 'ppda', 'fouls', 'corners', 'yellowCards', 'redCards', 'result']

# read_csv function which is used to read the required CSV file
data = pd.read_csv(PATH, encoding='latin-1')
data.fillna(-1, inplace=True)
data.assisterID = data.assisterID.astype(int)
# display 
print("Original 'input.csv' CSV Data: \n")
data = data[shots]
print(data)


# drop function which is used in removing or deleting rows or columns from the CSV files
#data.drop('understatNotation', inplace=True, axis=1)
  
# display 
print("\nCSV Data after deleting the column 'year':\n")
print(data)

data.to_csv(r'C:\Users\Ricardo\Desktop\Universidad\8vo semestre\Base de datos\Hitos\Proyecto-furbol\Main\Datos\buenos\shots.csv', index=True)
