#!"C:\Python27\python.exe"

import argparse
import random



def team(t, num_solutions):
    iterations = range(2, len(t)/2+1)

    totalscore = sum(t)
    halftotalscore = totalscore/2.0

    oldmoves = {}

    for p in t:
        people_left = t[:]
        people_left.remove(p)
        oldmoves[p] = people_left

    if iterations == []:
        solution = min(map(lambda i: (abs(float(i)-halftotalscore), i), oldmoves.keys()))
        return (solution[1], sum(oldmoves[solution[1]]), oldmoves[solution[1]])

    for n in iterations:
        newmoves = {}
        for total, roster in oldmoves.iteritems():
            for p in roster:
                people_left = roster[:]
                people_left.remove(p)
                newtotal = total+p
                if newtotal > halftotalscore: continue
                newmoves[newtotal] = people_left
        oldmoves = newmoves

    solutions = []

    for num_iter in range(num_solutions):
        key = min(map(lambda i: (abs(float(i)-halftotalscore), i), oldmoves.keys()))[1]
        solutions.append(oldmoves[key])
        del oldmoves[key]

    # random.shuffle( solutions )
    return solutions

# ===========================================================================
if __name__ == "__main__":
  parser = argparse.ArgumentParser(description='TeamGenerator')
  parser.add_argument('--numSolutions', help='number of solutions to generate', type=int)
  parser.add_argument('--numbers', help='list number', metavar='678 602 558 530 418 380 370 346 334 297', nargs='*', type=int)


  args = parser.parse_args()
  players = vars(parser.parse_args())['numbers']
  numSolutions = vars(parser.parse_args())['numSolutions']
  print numSolutions
#   file = open("c:/testfile.txt","w")
#   file.write(repr(args))
#   file.close()

  i = 0
  solutions = team(players, numSolutions)
  for solution in solutions:
    team_1 = solution
    team_2 = []
    for number in players:
        if number not in team_1:
            team_2.append(number)

    team_1_str = ' '.join(str(e) for e in team_1)
    team_2_str = ' '.join(str(e) for e in team_2)

    print team_1_str + "|" + team_2_str,

    team1_value = 0
    team2_value = 0
    for value_player in team_1:
        team1_value += value_player
    for value_player in team_2:
        team2_value += value_player

    i+=1
    if ( i < numSolutions ):
        print ";",

    # print team1_value
    # print team2_value
    # print abs(team2_value - team1_value)

